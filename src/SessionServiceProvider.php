<?php

namespace Rolandstarke\MildDatabaseSession;

use Rolandstarke\MildDatabaseSession\MildDatabaseSessionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('mild_database', function () {
            $table = $this->app['config']['session.table'];

            $lifetime = $this->app['config']['session.lifetime'];

            $connection = $this->app['config']['session.connection'];

            return new MildDatabaseSessionHandler(
                $this->app['db']->connection($connection), $table, $lifetime, $this->app
            );
        });
    }

}
