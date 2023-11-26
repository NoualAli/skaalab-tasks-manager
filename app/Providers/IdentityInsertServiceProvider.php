<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class IdentityInsertServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        DB::statement('
            EXEC sp_MSforeachtable
            "
            DECLARE @hasIdentity BIT
            SET @hasIdentity = (SELECT COUNT(*) FROM sys.columns WHERE object_id = object_id(\'?\') AND is_identity = 1)

            IF (@hasIdentity = 1)
            BEGIN
                SET IDENTITY_INSERT ? ON
            END
            "
        ');
    }
}
