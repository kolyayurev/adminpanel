<?php

namespace KY\AdminPanel\Commands;

use KY\AdminPanel\Traits\Seedable;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

class InstallCommand extends Command
{

    use Seedable;

    protected $seedersPath = __DIR__.'/../../database/seeders/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'adminpanel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Admin Panel';

    protected function getOptions()
    {
        return [
        ];
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publishing database, and config files');

        // $tags = ['adminpanel-assets'];

        // $this->call('vendor:publish', [ '--tag' => $tags]);

        $this->info('Migrating the database tables into your application');
        $this->call('migrate');

        $this->info('Attempting to set User model as parent to App\User');
        if (file_exists(app_path('Models/User.php'))) {
            $userPath = app_path('Models/User.php');

            $str = file_get_contents($userPath);

            if ($str !== false) {
                $str = str_replace('extends Authenticatable', "extends \KY\AdminPanel\Models\User", $str);

                file_put_contents($userPath, $str);
            }
        } else {
            $this->warn('Unable to locate "User.php" in app/Models.  Did you move this file?');
            $this->warn('You will need to update this manually.  Change "extends Authenticatable" to "extends \ KY\AdminPanel\Models\User" in your User model');
        }

        $this->info('Adding routes to routes/web.php');
        $routesContents = $filesystem->get(base_path('routes/web.php'));
        if (false === strpos($routesContents, 'AdminPanel::routes()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nRoute::group(['prefix' => config('adminpanel.prefix')], function () {\n    AdminPanel::routes();\n});\n"
            );
        }
        $this->info('Adding breadcrumbs routes to routes/breadcrumbs.php');
        if($filesystem->exists(base_path('routes/breadcrumbs.php')))
        {
            $breadcrumbsRoutesContents = $filesystem->get(base_path('routes/breadcrumbs.php'));
            if (false === strpos($breadcrumbsRoutesContents, 'AdminPanel::breadcrumbsRoutes()')) {
                $filesystem->append(
                    base_path('routes/breadcrumbs.php'),
                    "\n\nAdminPanel::breadcrumbsRoutes();\n"
                );
            }
        }
        else
        {
            $filesystem->put(
                base_path('routes/breadcrumbs.php'),
                 "<?php \n\nAdminPanel::breadcrumbsRoutes();\n",
            );

        }



        $this->info('Seeding data into the database');
        $this->seed('AdminPanelDatabaseSeeder');

        $this->call('vendor:publish', ['--tag' => ['adminpanel-config']]);

        $this->info('Adding the storage symlink to your public folder');
        $this->call('storage:link');

        $this->info('Clearing cache');
        $this->call('cache:clear');

        $this->info('Successfully installed Admin Panel! Enjoy');
    }
}
