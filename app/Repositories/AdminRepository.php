<?php

namespace Demoshop\Repositories;

use Demoshop\Model\Admin;

class AdminRepository
{
    /**
     * @param $username
     * @param $password
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdmin($username, $password): bool
    {
        $result = Admin::query()
            ->where('username', '=', $username)
            ->where('password', '=', $password)
            ->get();

        return !$result->isEmpty();
    }
}