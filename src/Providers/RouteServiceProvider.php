<?php

namespace Boytunghc\LaravelGitHook\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Boytunghc\LaravelGitHook\Controllers';

    public function map()
    {
        $this->mapHookRoutes();
    }

    /**
     * Define the "hook" routes for the package.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapHookRoutes()
    {
        Route::namespace($this->namespace)
             ->group(__DIR__ . '/../Routes/hook.php');
    }
}
