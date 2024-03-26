<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(string $email): ?User;
}
