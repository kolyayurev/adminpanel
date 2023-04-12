<?php

namespace KY\AdminPanel\Support;

use Illuminate\Support\Facades\Auth;
use Intervention\Image\Constraint;
use KY\AdminPanel\Support\Trumbnail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;
//use Gregwar\Image\Image as GregwarImage;

class APMedia
{

    /** @var string */
    private $filesystem;

    private $storage;

    public function __construct()
    {
        $this->filesystem = config('adminpanel.storage.disk');
        $this->storage = Storage::disk($this->filesystem);
    }

    public function storeAs($file, string $folder = '/', array $settings = []): string
    {
        $folder = $this->preparePath($folder, null, true);

        $filename = $this->generateFileName($file, $folder,$settings['filename'] ?? '');

        return $this->storage->putFileAs($folder, $file,$filename . '.' . $file->getClientOriginalExtension());
    }

    /**
     * Save image in storage.
     *
     * @param $file
     * @param string $folder
     * @param array $settings
     *
     * @return string $fullPath
     */
    public function saveImageFromFile($file, string $folder = 'images', array $settings = []): string
    {
        $folder = $this->preparePath($folder, null, true);
        // TODO: refactor
        // Имя файла
        $filename = $this->generateFileName($file, $folder,$settings['filename'] ?? '');

        $image = InterventionImage::make($file)->orientate();

        $fullPath = $folder . $filename . '.' . $file->getClientOriginalExtension();

        $resizeWidth = null;
        $resizeHeight = null;
        if (array_key_exists('resize', $settings) && (
                array_key_exists('width', $settings['resize']) || array_key_exists('height', $settings['resize'])
            )) {
            if (array_key_exists('width', $settings['resize'])) {
                $resizeWidth = $settings['resize']['width'];
            }
            if (array_key_exists('height', $settings['resize'])) {
                $resizeHeight = $settings['resize']['height'];
            }
        } else {
            $resizeWidth = $image->width();
            $resizeHeight = $image->height();
        }


        $image = $image->resize(
            $resizeWidth,
            $resizeHeight,
            function (Constraint $constraint) use ($settings) {
                $constraint->aspectRatio();
                if (array_key_exists('upsize', $settings) && !$settings['upsize']) {
                    $constraint->upsize();
                }
            }
        );


        return $this->saveImage($image, $fullPath, $settings);
    }

    public function saveImage($image, string $path, array $settings = []): string
    {
        $image = $image->encode($this->getExtFromPath($path), $settings['quality'] ?? 90);

        if ($this->storage->exists($path)) {
            $this->storage->delete($path);
        }

//        if ($this->is_animated_gif((string)$image)) {
//            $this->storage->put($fullPath, (string)$image, 'public');
//            $fullPathStatic = getImageThumb($fullPath,'static');
//            $this->storage->put($fullPathStatic, (string)$image, 'public');
//        } else {
        $this->storage->put($path, (string)$image, 'public');
//        }

        return $path;
    }


    /**
     * @param string $filePath
     * @param Trumbnail $thumbnail
     * @return string
     */
    public function thumbnail(string $filePath, Trumbnail $thumbnail): string
    {
        $fullPath = null;

        if ($this->getExtFromPath($filePath) !== 'svg') {
            if ($thumbnail->isCrop())
                $fullPath = $this->crop($filePath, $thumbnail->toArray(), $thumbnail->get('name'));

            if ($thumbnail->isScale())
                $fullPath = $this->scale($filePath, $thumbnail->toArray(), $thumbnail->get('name'));

            if ($thumbnail->isResize())
                $fullPath = $this->resize($filePath, $thumbnail->toArray(), $thumbnail->get('name'));

            if ($thumbnail->isFit())
                $fullPath = $this->fit($filePath, $thumbnail->toArray(), $thumbnail->get('name'));

        }


        return $fullPath;
    }
    public function thumbnailByArray(string $filePath, array $thumbnail): string
    {
        $fullPath = null;

        if ($this->getExtFromPath($filePath) !== 'svg') {
            switch ($thumbnail['type']) {
                case 'crop':
                    $fullPath = $this->crop($filePath, $thumbnail, $thumbnail['name'] ?? null);
                    break;
                case 'scale':
                    $fullPath = $this->scale($filePath, $thumbnail, $thumbnail['name'] ?? null);
                    break;
                case 'resize':
                    $fullPath = $this->resize($filePath, $thumbnail, $thumbnail['name'] ?? null);
                    break;
                case 'fit':
                    $fullPath = $this->fit($filePath, $thumbnail, $thumbnail['name'] ?? null);
                    break;
            }
        }

        return $fullPath;
    }

    /**
     * @param string $filePath
     * @param int $scale
     * @param string $suffix
     * @return string
     */
    public function scale(string $filePath, array $settings, string $suffix = null): string
    {
//        dump('scale',$settings);

        $image = InterventionImage::make($this->storage->path($filePath))->orientate();

        $scale = (intval($settings['scale']) / 100);
        $resizeWidth = intval($image->width() * $scale);
        $resizeHeight = intval($image->height() * $scale);

        $image->resize(
            $resizeWidth,
            $resizeHeight,
            function (Constraint $constraint) use ($settings) {
                $constraint->aspectRatio();
                if (array_key_exists('upsize', $settings) && !$settings['upsize']) {
                    $constraint->upsize();
                }
            }
        );
//        $image->resizeCanvas($resizeWidth, $resizeHeight, $settings['position'] ?? 'center', false, 'rgba(0, 0, 0, 0)');

        $fullPath = $this->getImageThumb($filePath, $suffix);

        return $this->saveImage($image, $fullPath, $settings);
    }

    public function crop(string $filePath, array $settings, string $suffix = null): string
    {
        $image = InterventionImage::make($this->storage->path($filePath))->orientate();

        //TODO: refactor
        $fullPath = $this->getImageThumb($filePath, $suffix);

        if ($image->width() < $settings['width'] || $image->height() < $settings['height']) {
            $image->fit(
                $settings['width'],
                ($settings['height'] ?? null),
                function ($constraint) {
                    $constraint->aspectRatio();
                },
                $settings['position']  ?? 'center'
            );
        }

        $image->crop(
            $settings['width'],
            $settings['height'],
            $settings['x'],
            $settings['y']
        );//->trim('transparent');

        if (!empty($settings['resultWidth']) && !empty($settings['resultHeight'])) {
            if ($image->width() != $settings['resultWidth'] || $image->height() != $settings['resultHeight']) {
                $image->fit(
                    $settings['resultWidth'],
                    ($settings['resultHeight'] ?? null),
                    function ($constraint) {
                        $constraint->aspectRatio();
                    },
                    $settings['position']  ?? 'center'
                );
            }

        }

        return $this->saveImage($image, $fullPath, $settings);
    }

    public function resize(string $filePath, array $settings, string $suffix = null): string
    {
        $image = InterventionImage::make($this->storage->path($filePath))->orientate();
//        dd('resize',$settings);


        if ($image->width() < $settings['width'] || $image->height() < $settings['height']) {
            $image->resizeCanvas($settings['width'], $settings['height'], $settings['position'] ?? 'center', false, 'rgba(0, 0, 0, 0)');
        } else {
            $image->resize(
                $settings['width'],
                ($settings['height'] ?? null),
                function ($constraint) use ($settings) {
                    $constraint->aspectRatio();
                    if (!($settings['upsize'] ?? true)) {
                        $constraint->upsize();
                    }
                }
            );
        }

        $fullPath = $this->getImageThumb($filePath, $suffix);

        return $this->saveImage($image, $fullPath, $settings);
    }

    public function fit(string $filePath, array $settings, string $suffix = null): string
    {
        $image = InterventionImage::make($this->storage->path($filePath))->orientate();

//        dd('fit',$settings);
        $image->fit(
            $settings['width'],
            ($settings['height'] ?? null),
            function ($constraint) {
                $constraint->aspectRatio();
            },
            $settings['position']  ?? 'center'
        );

        $fullPath = $this->getImageThumb($filePath, $suffix);

        return $this->saveImage($image, $fullPath, $settings);
    }

    public function deleteImage(string $removeImage)
    {
        // Удаляем старые изображения
        if (!empty($removeImage)) {
            $this->storage->delete([
                $removeImage,
            ]);
        }
    }

    public function preparePath(string $path, $key = null, bool $endSlash = false): string
    {
        $path = str_replace('{uid}', Auth::user()->getKey(), $path);

        $path = str_replace('{key}', $key, $path);

        if (Str::contains($path, '{date:')) {
            $path = preg_replace_callback('/\{date:([^\/\}]*)\}/', function ($date) {
                return \Carbon\Carbon::now()->format($date[1]);
            }, $path);
        }
        if (Str::contains($path, '{random:')) {
            $path = preg_replace_callback('/\{random:([0-9]+)\}/', function ($random) {
                return Str::random($random[1]);
            }, $path);
        }

        if (!Str::endsWith($path, '/') && $endSlash)
            $path .= '/';

        return $path;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param $path
     *
     * @return string
     */
    protected function generateFileName($file, $folder, $filename = null)
    {
        if (empty($filename)) {
            $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension());
            $filename = str_replace(' ', '-',$filename);
            $filename_counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            while ($this->storage->exists($folder . $filename . '.' . $file->getClientOriginalExtension())) {
                $filename = basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension()) . (string)($filename_counter++);
            }
        } else {
            $filename = $this->preparePath($filename);

            // Make sure the filename does not exist, if it does, just regenerate
            while ($this->storage->exists($folder . $filename . '.' . $file->getClientOriginalExtension())) {
                $filename = Str::random(20);
            }
        }
        return str_replace(' ', '-',$filename);
    }

    private function is_animated_gif($file)
    {
        $raw = file_get_contents($file);

        $offset = 0;
        $frames = 0;
        while ($frames < 2) {
            $where1 = strpos($raw, "\x00\x21\xF9\x04", $offset);
            if ($where1 === false) {
                break;
            } else {
                $offset = $where1 + 1;
                $where2 = strpos($raw, "\x00\x2C", $offset);
                if ($where2 === false) {
                    break;
                } else {
                    if ($where1 + 8 == $where2) {
                        $frames++;
                    }
                    $offset = $where2 + 1;
                }
            }
        }

        return $frames > 1;
    }

    /**
     * @param ?string $path
     * @param string $default
     * @return string
     */
    public function getUrl(?string $path, string $default = ''): string
    {
        if (!empty($path)) {
            return str_replace('\\', '/', Storage::url($path));
        }

        return $default;
    }

    /**
     *
     * @param ?string $path
     * @return string
     */
    public function getImageThumbUrl(?string $path, string $sufix = null): string
    {
        if (empty($path)) return '';
        return self::getUrl(self::getImageThumb($path, $sufix ?? config('adminpanel.media.default_thumb_name', 'thumb')));
    }

    /**
     *
     * @param ?string $path
     * @return string
     */

    public function getImageThumb(?string $path, string $suffix = null): string
    {
        if (is_null($suffix)) return $path;

        $ext = $this->getExtFromPath($path);

        $name = Str::replaceLast('.' . $ext, '', $path);

        return $name . '-' . Str::kebab($suffix) . '.' . $ext;
    }

    public function getExtFromPath(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);;
    }
}
