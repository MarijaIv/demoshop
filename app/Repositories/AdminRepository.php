<?php

namespace Demoshop\Repositories;

use Demoshop\Model\Admin;
use Illuminate\Support\Collection;

/**
 * Class AdminRepository
 * @package Demoshop\Repositories
 */
class AdminRepository
{
    /**
     * Check if admin exists for given username.
     *
     * @param string $username
     *
     * @return Collection
     */
    public function adminExists(string $username): Collection
    {
        return Admin::query()
            ->where('username', '=', $username)
            ->get();
    }
}