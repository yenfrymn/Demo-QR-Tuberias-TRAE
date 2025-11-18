<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Pipeline::class => \App\Policies\PipelinePolicy::class,
        \App\Models\Company::class => \App\Policies\CompanyPolicy::class,
        \App\Models\Certification::class => \App\Policies\CertificationPolicy::class,
        \App\Models\Blueprint::class => \App\Policies\BlueprintPolicy::class,
        \App\Models\OperatingLicense::class => \App\Policies\OperatingLicensePolicy::class,
        \App\Models\PipelineCompany::class => \App\Policies\PipelineCompanyPolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('manage-pipelines', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
        Gate::define('manage-companies', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
        Gate::define('manage-certifications', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
        Gate::define('manage-blueprints', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
        Gate::define('manage-licenses', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
        Gate::define('manage-pipeline-companies', function (User $user) {
            return in_array($user->role, ['admin', 'editor']);
        });
    }
}