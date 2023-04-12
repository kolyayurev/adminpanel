<?php

namespace KY\AdminPanel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use KY\AdminPanel\Facades\APMedia;

class AdminPanelController extends Controller
{

    public function dashboard()
    {
        return !empty(config('adminpanel.redirects.dashboard'))?
            redirect(config('adminpanel.redirects.dashboard'))
            :view('adminpanel::pages.dashboard');
    }

    /**
     * Get Modal.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getModal(Request $request)
    {
        $data = $request->extraData['existData'] ?? $request->existData ?? [];
        return response()->json([
            'status' => true,
            'template' => view($request->modalName, ['data' => $data])->render()
        ]);
    }

    public function assets(Request $request)
    {
        try {
            $path = dirname(__DIR__, 3).'/public/'.static::normalizeRelativePath(urldecode($request->path));
        } catch (\LogicException $e) {
            abort(404);
        }

        if (File::exists($path)) {
            $mime = '';
            if (Str::endsWith($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (Str::endsWith($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        return response('', 404);
    }

    /**
     * Normalize relative directories in a path.
     *
     * @param string $path
     *
     * @throws LogicException
     *
     * @return string
     */
    protected static function normalizeRelativePath($path)
    {
        $path = str_replace('\\', '/', $path);
        $path = static::removeFunkyWhiteSpace($path);

        $parts = [];

        foreach (explode('/', $path) as $part) {
            switch ($part) {
                case '':
                case '.':
                break;

            case '..':
                if (empty($parts)) {
                    throw new LogicException(
                        'Path is outside of the defined root, path: [' . $path . ']'
                    );
                }
                array_pop($parts);
                break;

            default:
                $parts[] = $part;
                break;
            }
        }

        return implode('/', $parts);
    }

    /**
     * Removes unprintable characters and invalid unicode characters.
     *
     * @param string $path
     *
     * @return string $path
     */
    protected static function removeFunkyWhiteSpace($path) {
        // We do this check in a loop, since removing invalid unicode characters
        // can lead to new characters being created.
        while (preg_match('#\p{C}+|^\./#u', $path)) {
            $path = preg_replace('#\p{C}+|^\./#u', '', $path);
        }

        return $path;
    }

}
