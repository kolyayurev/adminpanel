<?php

namespace KY\AdminPanel;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use KY\AdminPanel\Commands\InstallCommand;
use KY\AdminPanel\Commands\MakeDataControllerCommand;
use KY\AdminPanel\Commands\MakeDataTypeCommand;
use KY\AdminPanel\Commands\MakePageTypeCommand;
use KY\AdminPanel\Commands\MakeRepositoryCommand;
use KY\AdminPanel\DataTypes\RedirectDataType;
use KY\AdminPanel\DataTypes\RoleDataType;
use KY\AdminPanel\DataTypes\SefDataType;
use KY\AdminPanel\DataTypes\SeoDataType;
use KY\AdminPanel\DataTypes\SettingDataType;
use KY\AdminPanel\DataTypes\TestDataType;
use KY\AdminPanel\DataTypes\UserDataType;
use KY\AdminPanel\Facades\AdminPanel as AdminPanelFacade;
use KY\AdminPanel\Facades\APMedia as APMediaFacade;
use KY\AdminPanel\Menus\AdminMenu;
use KY\AdminPanel\Http\Middleware\AdminMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use KY\AdminPanel\Policies\BasePolicy;


class AdminPanelServiceProvider extends ServiceProvider
{
    protected $vendor  = 'kolyayurev';
    protected $name  = 'adminpanel';

    protected $policies = [
    ];

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->loadHelpers();

        $loader = AliasLoader::getInstance();
        $loader->alias('AdminPanel', AdminPanelFacade::class);

        $this->app->singleton('adminpanel', function () {
            return new AdminPanel();
        });

        $this->app->singleton('AdminPanelGuard', function () {
            return config('auth.defaults.guard', 'web');
        });

        $loader->alias('APMedia', APMediaFacade::class);
        $this->app->bind('APMedia', function () {
            return new APMedia();
        });

        if ($this->app->runningInConsole()) {
            $this->registerPublishableResources();
            $this->registerConsoleCommands();
        }
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', $this->name
        );

        //TODO:check is admin route
        $this->registerDataTypes();
//        AdminPanelFacade::addMenu(AdminMenu::class);

    }

    /**
     * Bootstrap
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('admin.user', AdminMiddleware::class);

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        $this->loadAuth();

        $this->loadTranslationsFrom(__DIR__.'/../lang', $this->name);
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->name);

        Blade::componentNamespace('KY\\AdminPanel\\View\\Components', $this->name);
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    public function loadAuth()
    {

        try {
            $dataTypes = AdminPanelFacade::getDataTypes();

            foreach ($dataTypes as $dataType) {
                $policyClass = BasePolicy::class;
                if (!empty($dataType->getPolicy()) && class_exists($dataType->getPolicy())) {
                    $policyClass = $dataType->getPolicy();
                }

                $this->policies[$dataType->repository->modelClass()] = $policyClass;
            }
            $this->policies[AdminPanelFacade::modelClass('Setting')] = BasePolicy::class;
            $this->registerPolicies();
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        // Gates
        foreach (config('adminpanel.gates', []) as $name => $value){
            Gate::define($name, function ($user) use ($value){
                return $value;
            });
        }
        Gate::define('view_admin', function ($user) {
            return $user->hasRole('admin');
        });
    }

    /**
     * Register the publishable files.
     */
    private function registerPublishableResources()
    {
        $publishable = [
            'assets' => [
                __DIR__."/../public" => public_path('vendor'.DIRECTORY_SEPARATOR.$this->name),
            ],
            'config' => [
                __DIR__.'/../config/config.php' => config_path($this->name.'.php')
            ],
            'lang' => [
                __DIR__.'/../lang' => $this->app->langPath('vendor'.DIRECTORY_SEPARATOR.$this->name),
            ],
            'migrations' => [
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ],
            'seeds' => [
                __DIR__.'/../database/seeders/' => database_path('seeders'),
            ],
        ];

        foreach ($publishable as $group => $paths) {

            $this->publishes($paths,"{$this->name}-{$group}");
        }

    }

    /**
     * Register the commands
     */
    private function registerConsoleCommands()
    {
        $this->commands(InstallCommand::class);
        $this->commands(MakeRepositoryCommand::class);
        $this->commands(MakeDataTypeCommand::class);
        $this->commands(MakePageTypeCommand::class);
        $this->commands(MakeDataControllerCommand::class);
    }

    protected function registerDataTypes()
    {
        AdminPanelFacade::addDataType(UserDataType::class);
        AdminPanelFacade::addDataType(RoleDataType::class);
        AdminPanelFacade::addDataType(TestDataType::class);
        AdminPanelFacade::addDataType(RedirectDataType::class);
        AdminPanelFacade::addDataType(SeoDataType::class);
        AdminPanelFacade::addDataType(SefDataType::class);
    }
}
