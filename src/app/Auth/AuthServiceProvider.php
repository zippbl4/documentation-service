<?php

namespace App\Auth;

use App\Auth\Guards\GwGuard;
use App\Auth\UserProviders\GwUserProvider;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // add custom guard provider
        Auth::provider('gw', function (Container $app, array $config) {
            return $app->make(GwUserProvider::class);
        });

        // add custom guard
        Auth::extend('gw', function (Container $app, string $name, array $config) {
            $guard = new GwGuard(
                $name,
                Auth::createUserProvider($config['provider']),
                $app->get('session.store'),
            );

            /**
             * @see AuthManager::createSessionDriver()
             */
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            if (isset($config['remember'])) {
                $guard->setRememberDuration($config['remember']);
            }

            return $guard;
        });
    }
}
