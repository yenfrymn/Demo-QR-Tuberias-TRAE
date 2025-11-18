<?php

namespace App\Policies;

use App\Models\PipelineCompany;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PipelineCompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, PipelineCompany $pipelineCompany): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function update(User $user, PipelineCompany $pipelineCompany): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function delete(User $user, PipelineCompany $pipelineCompany): bool
    {
        return $user->role === 'admin';
    }
}