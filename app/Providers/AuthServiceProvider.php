<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use App\Policies\PermissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Permission' => PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
       
        $this->registerPolicies();
        Gate::define('has-permission', [PermissionPolicy::class, 'hasPermission']);
    }
}
