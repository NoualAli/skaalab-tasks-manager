<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

// use Martinezart87\CustomSqlServerConnector\CustomSqlServerConnector;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningUnitTests()) {
            Schema::defaultStringLength(191);
        }

        EloquentBuilder::macro('whereLike', function ($column, $search) {
            return $this->where($column, 'LIKE', "%{$search}%");
        });

        QueryBuilder::macro('whereLike', function ($column, $search) {
            return $this->where($column, 'LIKE', "%{$search}%");
        });

        QueryBuilder::macro('search', function ($columns, $search) {
            return $this->where(function ($query) use ($columns, $search) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$search}%");
                }
            });
        });

        QueryBuilder::macro('sortByMultiple', function ($columns) {
            foreach ($columns as $key => $value) {
                $query = $this->orderBy($key, $value);
            }
            return $query;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('db.connector.sqlsrv', CustomSqlServerConnector::class);

        if ($this->app->environment('local', 'testing') && class_exists(DuskServiceProvider::class)) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
