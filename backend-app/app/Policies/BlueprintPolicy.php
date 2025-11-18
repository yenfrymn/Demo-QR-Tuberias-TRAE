<?php

namespace App\Policies;

use App\Models\Blueprint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlueprintPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Blueprint $blueprint): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, Blueprint $blueprint): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, Blueprint $blueprint): bool
    {
        return $user->role === 'admin';
    }
}