<?php

namespace App\Policies;

use App\Models\OperatingLicense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OperatingLicensePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, OperatingLicense $operatingLicense): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, OperatingLicense $operatingLicense): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, OperatingLicense $operatingLicense): bool
    {
        return $user->role === 'admin';
    }
}