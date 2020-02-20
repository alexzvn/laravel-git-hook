<?php

namespace Boytunghc\LaravelGitHook\Providers;

use Boytunghc\LaravelGitHook\Commands\CreateDeployKey;
use Illuminate\Foundation\Application as Laravel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            if ($this->app instanceof Laravel) {
                $this->publishes([
                    __DIR__ . '/../../config/githook.php' => config_path('githook.php')
                ], 'config');
            }
        }

        // $this->loadViewsFrom(__DIR__ . '/../../Views', 'Boytunghc\LaravelGitHook');

        $this->commands([CreateDeployKey::class]);

        $this->app->bind('Boytunghc\LaravelGitHook\Contracts\HookInterface', function () {
            $drive = config('githook.drive');

            if ($drive === 'github') {
                return new \Boytunghc\LaravelGitHook\Controllers\Hook\Github(request());
            } elseif ($drive === 'gitlab') {
                return new \Boytunghc\LaravelGitHook\Controllers\Hook\Gitlab(request());
            }

            throw new \Exception('Config githook.drive is invalid', 1);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/githook.php', 'githook');
    }
}