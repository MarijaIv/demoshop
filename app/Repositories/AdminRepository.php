<?php

namespace Demoshop\Repositories;

use Demoshop\Model\Admin;

/**
 * Class AdminRepository
 * @package Demoshop\Repositories
 */
class AdminRepository
{
    /**
     * Get admin with given username.
     *
     * @param string $username
     *
     * @return Admin|null
     */
    public function getAdminWithUsername(string $username): ?Admin
    {
        return Admin::query()
            ->where('username', '=', $username)
            ->first();
    }

    /**
     * Check if admin with given username exists.
     *
     * @param string $username
     * @return bool
     */
    public function adminExists(string $username): bool
    {
        return Admin::query()
            ->where('username', '=', $username)
            ->exists();
    }
}