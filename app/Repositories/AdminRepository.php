<?php

namespace Demoshop\Repositories;

use Demoshop\Model\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    /**
     * Get all admins.
     *
     * @return Collection
     */
    public function getAllAdmins(): Collection
    {
        return Admin::query()->select('username', 'password')->get();
    }
}