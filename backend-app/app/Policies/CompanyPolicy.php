<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Company $company): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, Company $company): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->role === 'admin';
    }
}