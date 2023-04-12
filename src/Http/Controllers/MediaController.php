<?php

namespace KY\AdminPanel\Http\Controllers;

use AdminPanel, APMedia, Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use KY\AdminPanel\FormFields\MediaPicker;
use KY\AdminPanel\Support\Watermark;


class MediaController extends Controller
{
    /** @var string */
    private $filesystem;

    private $storage;

    /** @var string */
    private $directory = '';

    public function __construct()
    {
        $this->filesystem = config('adminpanel.storage.disk');
        $this->storage = Storage::disk($this->filesystem);
    }

    public function index()
    {
        // Check permission
        $this->authorize('view_media');

        return view('adminpanel::media.index');
    }

    public function files(Request $request)
    {
        // Check permission
        $this->authorize('view_media');

        $thumbnail_names = [];
        $thumbnails = [];

        $thumbnails = json_decode($request->thumbnails,true);
        $hideThumbnails = $request->get('hide_thumbnails') === 'true';

        if (!empty($thumbnails) && $hideThumbnails) {
            foreach ($thumbnails as $thumbnail)
            {
                if(!empty($thumbnail['name']))
                $thumbnail_names[] = $thumbnail['name'];
            }

        }

        $folder = $request->folder;

        if ($folder == '/') {
            $folder = '';
        }

        $dir = $this->directory . $folder;

        $files = [];

        $storageItems = $this->storage->listContents($dir);

        foreach ($storageItems as $item) {
            $basename = pathinfo($item->path(), PATHINFO_FILENAME);

            if ($item->isDir()) {
                $files[] = [
                    'name' => $basename,
                    'type' => 'folder',
                    'path' => $this->storage->url($item->path()),
                    'relative_path' => $item->path(),
                    'items' => '',
                    'last_modified' => '',
                ];
            } else {
                if (empty($basename) && !config('adminpanel.hidden_files')) {
                    continue;
                }
                $filename = Str::afterLast($item->path(), '/');

                $itemArray = [
                    'name' => $basename,
                    'filename' => $filename,
                    'type' => $this->storage->mimeType($item->path()) ?? 'file',
                    'path' => $this->storage->url($item->path()),
                    'relative_path' => $item->path(),
                    'size' => $item->fileSize(),
                    'last_modified' => $item->lastModified(),
                    'thumbnails' => [],
                ];

                // Its a thumbnail and thumbnails should be hidden
                if (Str::endsWith($basename, $thumbnail_names)) {
                    $thumbnails[] = $itemArray;
                    continue;
                }
                $files[] = $itemArray;
            }
        }

        foreach ($files as $key => $file) {
            foreach ($thumbnails as $thumbnail) {
                if ($file['type'] != 'folder' && Str::startsWith($thumbnail['name'], $file['name'])) {
                    $thumbnail['thumb_name'] = str_replace($file['name'] . '-', '', $thumbnail['name']);
                    if(in_array($thumbnail['thumb_name'],$thumbnail_names))
                        $files[$key]['thumbnails'][] = $thumbnail;
                }
            }
        }
        $files = collect($files)->keyBy('name')->sortKeysUsing('strnatcasecmp')->values();
        return response()->json($files->toArray());
    }

    public function newFolder(Request $request)
    {
        // Check permission
        $this->authorize('view_media');

        $new_folder = $request->new_folder;
        $status = false;
        $error = '';

        if ($this->storage->exists($new_folder)) {
            $error = ap_trans('media.folder_exists_already');
        } elseif ($this->storage->makeDirectory($new_folder)) {
            $status = true;
        } else {
            $error = ap_trans('media.error_creating_dir');
        }

        return compact('status', 'error');
    }

    public function delete(Request $request)
    {
        // Check permission
        $this->authorize('view_media');

        $path = str_replace('//', '/', Str::finish($request->path, '/'));
        $thumbnails = json_decode($request->thumbnails,true);
        $status = true;
        $error = '';

        foreach ($request->get('files') as $file) {
            if ($file['type'] == 'folder') {
                if (!$this->storage->deleteDirectory($file['relative_path'])) {
                    $error = ap_trans('media.error_deleting_folder');
                    $status = false;
                }
            } else {

                if (!$this->storage->delete($file['relative_path'])){
                    $error = ap_trans('media.error_deleting_file');
                    $status = false;
                }


                if(!empty($thumbnails)) {
                    foreach ($thumbnails as $thumbnail) {
                        $this->storage->delete(APMedia::getImageThumb($file['relative_path'], $thumbnail['name']));
                    }
                }

            }
        }

        return compact('status', 'error');
    }

    public function move(Request $request)
    {
        // Check permission
        $this->authorize('view_media');
        $path = str_replace('//', '/', Str::finish($request->path, '/'));
        $dest = str_replace('//', '/', Str::finish($request->destination, '/'));
        if (strpos($dest, '/../') !== false) {
            $dest = substr($path, 0, -1);
            $dest = substr($dest, 0, strripos($dest, '/') + 1);
        }
        $dest = str_replace('//', '/', Str::finish($dest, '/'));

        $status = true;
        $error = '';

        foreach ($request->get('files') as $file) {
            $old_path = $path . $file['filename'];
            $new_path = $dest . $file['filename'];
            try {
                $this->storage->move($old_path, $new_path);
            } catch (\Exception $ex) {
                $status = false;
                $error = $ex->getMessage();

                return compact('status', 'error');
            }
        }

        return compact('status', 'error');
    }

    public function rename(Request $request)
    {
        // Check permission
        $this->authorize('view_media');

        $folderLocation = $request->folder_location;
        $filename = $request->filename;
        $newName = $request->new_name;
        $status = false;
        $error = false;

        if (is_array($folderLocation)) {
            $folderLocation = rtrim(implode('/', $folderLocation), '/');
        }

        $location = "{$this->directory}/{$folderLocation}";

        if (!$this->storage->exists("{$location}/{$newName}")) {
            $ext = APMedia::getExtFromPath("{$location}/{$filename}");
            if ($this->storage->move("{$location}/{$filename}", "{$location}/{$newName}.{$ext}")) {
                $status = true;
            } else {
                $error = ap_trans('media.error_moving');
            }
        } else {
            $error = ap_trans('media.error_may_exist');
        }

        return compact('status', 'error');
    }

    public function upload(Request $request)
    {
        //TODO:remake
        // Check permission
        $this->authorize('view_media');

        $thumbnails = json_decode($request->thumbnails,true);
        $filename = !empty($request->get('filename')) ? $request->get('filename') : null;

        try {
            $realPath = '';//$this->storage->getDriver()->getAdapter()->getPathPrefix();

            $allowedMimeTypes = config('adminpanel.media.allowed_mimetypes', '*');
            if ($allowedMimeTypes != '*' && (is_array($allowedMimeTypes) && !in_array($request->file->getMimeType(), $allowedMimeTypes))) {
                throw new Exception(ap_trans('generic.mimetype_not_allowed'));
            }

            $path = APMedia::storeAs($request->file,$request->upload_path,['filename' => $filename]);
            $path = preg_replace('#/+#', '/', $path);

            $imageMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/bmp',
//                'image/svg+xml',
            ];
            if (in_array($request->file->getMimeType(), $imageMimeTypes)) {
                $image = Image::make($this->storage->get($path));

                if ($request->file->getClientOriginalExtension() == 'gif') {
                    copy($request->file->getRealPath(), $realPath . $path);
                } else {
                    $image = $image->orientate();
                    // Add watermark to image
//                    if ($field && $field->hasWatermark()) {
//                        $image = $this->addWatermarkToImage($image, $field->getWatermark());
//                        APMedia::saveImage($image,$path,['quality'=>$field->get('quality'),'filename'=>$field->get('filename')]);
//                    }
//                    else
//                    {
                        APMedia::saveImage($image,$path);
//                    }

                    if(!empty($thumbnails)) {
                        foreach ($thumbnails as $thumbnail) {
                            APMedia::thumbnailByArray($path, $thumbnail);
                        }
                    }
                }
            }

            $status = true;
            $message = ap_trans('media.success_uploaded_file');
            $path = preg_replace('/^public\//', '', $path);

        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $path = '';
        }

        return response()->json(compact('status', 'message', 'path'));
    }

    public function crop(Request $request)
    {
        // Check permission
        $this->authorize('view_media');

        $createMode = $request->get('create_mode') === 'true';
        $x = $request->get('x');
        $y = $request->get('y');
        $minWidth = $request->get('minWidth');
        $minHeight = $request->get('minHeight');
        $height = $request->get('height');
        $width = $request->get('width');
        $cropName = $request->get('name',null);

        $realPath = '';//$this->storage->getDriver()->getAdapter()->getPathPrefix();
        $originImagePath = $request->upload_path . '/' . $request->originImageName;
        $originImagePath = preg_replace('#/+#', '/', $originImagePath);

        try {
            if ($createMode) {
                // create a new image with the cpopped data
                $path = APMedia::crop($originImagePath,['resultWidth'=>$minWidth,'resultHeight'=>$minHeight,'width'=>$width,'height'=>$height,'x'=>$x,'y'=>$y],!empty($cropName) ?$cropName: '_cropped_' . time());

                $thumbnails = json_decode($request->thumbnails,true);
                //если $cropName не пустое, занчит это пересоздание миниатюры
                if(empty($cropName) && !empty($thumbnails)) {
                    foreach ($thumbnails as $thumbnail) {
                        APMedia::thumbnailByArray($path, $thumbnail);
                    }
                }
            } else {
                // override the original image
                APMedia::crop($originImagePath,['resultWidth'=>$minWidth,'resultHeight'=>$minHeight,'width'=>$width,'height'=>$height,'x'=>$x,'y'=>$y],!empty($cropName)?$cropName:null);
            }

            $status = true;
            $message = ap_trans('media.success_crop_image');
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
        }

        return response()->json(compact('status', 'message'));
    }

    private function addWatermarkToImage($image, Watermark $watermark)
    {
        $watermarkImage = Image::make($this->storage->path($watermark->get('source')));
        // Resize watermark
        $width = $image->width() * ($watermark->get('size') / 100);
        $watermarkImage->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $image->insert(
            $watermarkImage,
            $watermark->get('position'),
            $watermark->get('x'),
            $watermark->get('y'),
        );
    }

}
