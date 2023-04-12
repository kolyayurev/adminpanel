<?php

namespace KY\AdminPanel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use KY\AdminPanel\Contracts\DataTypeContract;
use KY\AdminPanel\Contracts\MenuContract;
use KY\AdminPanel\Contracts\PageTypeContract;
use KY\AdminPanel\Models\Permission;
use KY\AdminPanel\Models\Role;
use KY\AdminPanel\Models\Redirect;
use KY\AdminPanel\Models\Sef;
use KY\AdminPanel\Models\Seo;
use KY\AdminPanel\Models\Setting;
use KY\AdminPanel\Models\Translation;
use KY\AdminPanel\Models\User;

class AdminPanel
{
    protected $version;
    protected $branch;
    protected $filesystem;

    protected array $menus = [];

    protected array $pageTypes = [];

    protected array $dataTypes = [];

    public $setting_cache = null;

    protected $models = [
        'Redirect'    => Redirect::class,
        'Permission'  => Permission::class,
        'Role'        => Role::class,
        'Setting'     => Setting::class,
        'Sef'         => Sef::class,
        'Seo'         => Seo::class,
        'User'        => User::class,
        'Translation' => Translation::class,
    ];


    public function __construct()
    {
        $this->filesystem = app(Filesystem::class);
        $this->findPackageInformation();
    }

    public function model($name)
    {
        return app($this->models[Str::studly($name)]);
    }

    public function modelClass($name)
    {
        return $this->models[$name];
    }

    public function useModel($name, $object)
    {
        if (is_string($object)) {
            $object = app($object);
        }

        $class = get_class($object);

        if (isset($this->models[Str::studly($name)]) && !$object instanceof $this->models[Str::studly($name)]) {
            throw new \Exception("[{$class}] must be instance of [{$this->models[Str::studly($name)]}].");
        }

        $this->models[Str::studly($name)] = $class;

        return $this;
    }

    public function setting($key, $default = null, $locale = null)
    {
        $isModelTranslatable = is_translatable(AdminPanel::model('Setting'));

        if($isModelTranslatable && is_null($locale)){
            $locale = app()->currentLocale();
            $key = $key.'_'.$locale;
        }

        $globalCache = config('adminpanel.settings.cache', false);

        if ($globalCache && Cache::tags('settings')->has($key)) {
            return Cache::tags('settings')->get($key);
        }


        if ($this->setting_cache === null) {
            if ($globalCache) {
                // A key is requested that is not in the cache
                // this is a good opportunity to update all keys
                // albeit not strictly necessary
                Cache::tags('settings')->flush();
            }
            $settings = self::model('Setting')->get();
            if($isModelTranslatable){

                $defaultLocale = config('adminpanel.multilingual.default', 'ru');
                $locales = config('adminpanel.multilingual.locales', [$defaultLocale]);

                $settings->load('translations');

                foreach ($settings as $setting) {
                    foreach ($locales as $l){
                        $settingKey = $setting->key.'_'.$l;
                        $value = $setting->getTranslatedAttribute('value',$l);
                        @$this->setting_cache[$settingKey] = $value;

                        if ($globalCache) {
                            Cache::tags('settings')->forever($settingKey, $value);
                        }
                    }
                }
            }
            else {
                foreach ($settings as $setting) {
                    @$this->setting_cache[$setting->key] = $setting->value;

                    if ($globalCache) {
                        Cache::tags('settings')->forever($setting->key, $setting->value);
                    }
                }
            }


        }
        return @$this->setting_cache[$key] ?: $default;
    }

    public function routes()
    {
        require __DIR__.'/../routes/web.php';
    }

    public function breadcrumbsRoutes()
    {
        require __DIR__.'/../routes/breadcrumbs.php';
    }

    public function addMenu($handler)
    {
        if (!$handler instanceof MenuContract) {
            $handler = app($handler);
        }

        $this->menus[$handler->getSlug()] = $handler;

        return $this;
    }

    public function getMenu($name)
    {
        $menu = $this->menus[$name];

        return $menu->handle();
    }

    public function addPageType($handler)
    {
        if (!$handler instanceof PageTypeContract) {
            $handler = app($handler);
        }

        $this->pageTypes[$handler->getSlug()] = $handler;

        return $this;
    }

    public function getPageType($slug)
    {
        return  array_key_exists($slug,$this->pageTypes)?$this->pageTypes[$slug]:null;
    }

    public function getPageTypes()
    {
        return  collect($this->pageTypes);
    }

    public function addDataType($handler)
    {
        if (!$handler instanceof DataTypeContract) {
            $handler = app($handler);
        }

        $this->dataTypes[$handler->getSlug()] = $handler;

        return $this;
    }

    public function getDataType($slug)
    {
        return  array_key_exists($slug,$this->dataTypes)?$this->dataTypes[$slug]:null;
    }

    public function getDataTypes()
    {
        return  collect($this->dataTypes);
    }


    public function getVersion()
    {
        return $this->version;
    }
    public function getBranch()
    {
        return $this->branch;
    }

    protected function findPackageInformation()
    {
        if (is_null($this->version) && $this->filesystem->exists(base_path('composer.lock'))) {
            // Get the composer.lock file
            $file = json_decode(
                $this->filesystem->get(base_path('composer.lock'))
            );

            // Loop through all the packages and get the version of AdminPanel
            foreach ($file->packages as $package) {
                if ($package->name == 'kolyayurev/adminpanel') {
                    $this->version = $package->version;
                    break;
                }
            }
        }
        if (is_null($this->branch) && $this->filesystem->exists(base_path('.git/HEAD'))) {
            $file = $this->filesystem->get(base_path('.git/HEAD'));

            $this->branch = rtrim(preg_replace("/(.*?\/){2}/", '', $file));
        }

    }
}
