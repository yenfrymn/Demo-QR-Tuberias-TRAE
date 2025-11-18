<?php

namespace App\Policies;

use App\Models\Certification;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CertificationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Certification $certification): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, Certification $certification): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, Certification $certification): bool
    {
        return $user->role === 'admin';
    }
}