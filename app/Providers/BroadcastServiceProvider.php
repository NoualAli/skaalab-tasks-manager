<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            if (request()->hasHeader('authorization')) {
                Broadcast::routes(["prefix" => "api", "middleware" => "auth:api"]);
            } else {
                Broadcast::routes();
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }

        require base_path('routes/channels.php');
    }
}
