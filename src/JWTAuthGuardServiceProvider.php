<?php

namespace LukeVear\JWTAuthGuard;

use Illuminate\Support\ServiceProvider;

class JWTAuthGuardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the 'jwt-auth' driver
        $this->app['auth']->extend('jwt-auth', function ($app, $name, array $config) {
            $guard = new AuthGuard(
                $app['tymon.jwt.auth'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $this->app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
