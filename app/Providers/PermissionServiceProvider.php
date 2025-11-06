<?php

namespace App\Providers;

use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        // @hasPermission directive
        Blade::if('hasPermission', function ($permission) {
            return PermissionHelper::hasPermission($permission);
        });

        // @canCreate directive
        Blade::if('canCreate', function ($permission) {
            return PermissionHelper::canCreate($permission);
        });

        // @canRead directive
        Blade::if('canRead', function ($permission) {
            return PermissionHelper::canRead($permission);
        });

        // @canUpdate directive
        Blade::if('canUpdate', function ($permission) {
            return PermissionHelper::canUpdate($permission);
        });

        // @canDelete directive
        Blade::if('canDelete', function ($permission) {
            return PermissionHelper::canDelete($permission);
        });

        // @isAdmin directive
        Blade::if('isAdmin', function () {
            return PermissionHelper::isAdmin();
        });

        // @isOperator directive
        Blade::if('isOperator', function () {
            return PermissionHelper::isOperator();
        });

        // @isFinanceHR directive
        Blade::if('isFinanceHR', function () {
            return PermissionHelper::isFinanceHR();
        });
    }
}
