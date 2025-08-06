<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class TenantRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // route tenant default
        Route::middleware([
            'web',
            InitializeTenancyByDomain::class,
            PreventAccessFromCentralDomains::class,
        ])->group(base_path('routes/tenant.php'));

        // load semua module routes
        foreach (glob(base_path('Modules/*/Routes/web.php')) as $routeFile) {
            Route::middleware([
                'web',
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class,
            ])->group($routeFile);
        }
    }
}
