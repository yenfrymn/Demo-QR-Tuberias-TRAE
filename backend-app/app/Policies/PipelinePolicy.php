<?php

namespace App\Policies;

use App\Models\Pipeline;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PipelinePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Pipeline $pipeline): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, Pipeline $pipeline): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, Pipeline $pipeline): bool
    {
        return $user->role === 'admin';
    }

    public function generateQr(User $user, Pipeline $pipeline): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function downloadQr(User $user, Pipeline $pipeline): bool
    {
        return true;
    }
}