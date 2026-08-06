<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Permohonan;
use App\Policies\PermohonanPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Permohonan::class, PermohonanPolicy::class);
    }
}
