<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Permohonan;
use App\Policies\PermohonanPolicy;
use App\Policies\ApprovalPolicy;
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

        Gate::define('approveAsAtasan', [ApprovalPolicy::class, 'approveAsAtasan']);
        Gate::define('approveAsDirut',  [ApprovalPolicy::class, 'approveAsDirut']);
        Gate::define('rejectPermohonan', [ApprovalPolicy::class, 'reject']);
        Gate::define('revisePermohonan', [ApprovalPolicy::class, 'revise']);
    }
}
