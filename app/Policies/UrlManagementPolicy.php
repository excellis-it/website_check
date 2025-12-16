<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UrlManagement;

class UrlManagementPolicy
{
    /**
     * Determine if the user can perform any action (super admin override).
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('SUPER ADMIN')) {
            return true;
        }
    }

    /**
     * Determine if the user can view any URLs.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-urls');
    }

    /**
     * Determine if the user can view the URL.
     */
    public function view(User $user, UrlManagement $url): bool
    {
        if ($user->can('view-urls')) {
            // Check if user is assigned to this URL OR has manage-urls permission
            if ($user->can('manage-urls')) {
                return true;
            }
            return $url->assignedUsers()->where('user_id', $user->id)->exists();
        }
        return false;
    }

    /**
     * Determine if the user can create URLs.
     */
    public function create(User $user): bool
    {
        return $user->can('create-urls');
    }

    /**
     * Determine if the user can update the URL.
     */
    public function update(User $user, UrlManagement $url): bool
    {
        return $user->can('edit-urls');
    }

    /**
     * Determine if the user can delete the URL.
     */
    public function delete(User $user, UrlManagement $url): bool
    {
        return $user->can('delete-urls');
    }
}
