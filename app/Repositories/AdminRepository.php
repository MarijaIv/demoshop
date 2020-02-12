<?php

namespace Demoshop\Repositories;

use Demoshop\Model\Admin;

class AdminRepository
{
    /**
     * Check if admin exists for given username.
     *
     * @param string $username
     *
     * @return bool
     */
    public function adminExists(string $username): bool
    {
        $result = Admin::query()
            ->where('username', '=', $username)
            ->get();

        return !$result->isEmpty();
    }
}