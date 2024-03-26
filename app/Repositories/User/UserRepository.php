<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    private User $model;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->query()->where('email', $email)->first();
    }
}
