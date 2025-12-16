<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UrlManagement;

class UrlManagementPolicy
{
    /**
     * Determine if the user can view any URLs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view URLs they have access to
    }

    /**
     * Determine if the user can view the URL.
     */
    public function view(User $user, UrlManagement $url): bool
    {
        // Super admin can view all
        if ($user->hasRole('ADMIN')) {
            return true;
        }

        // Check if user is assigned to this URL
        return $url->assignedUsers()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine if the user can create URLs.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('ADMIN');
    }

    /**
     * Determine if the user can update the URL.
     */
    public function update(User $user, UrlManagement $url): bool
    {
        return $user->hasRole('ADMIN');
    }

    /**
     * Determine if the user can delete the URL.
     */
    public function delete(User $user, UrlManagement $url): bool
    {
        return $user->hasRole('ADMIN');
    }
}
