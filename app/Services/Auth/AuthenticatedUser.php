<?php

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class AuthenticatedUser
{
    private Authenticatable $user;

    /**
     * @var string
     */
    private string $token;

    /**
     * @var int
     */
    private int $expiration;

    /**
     * @var string
     */
    private string $role;

    /**
     * @param Authenticatable $user
     * @param string          $role
     * @param string|         $token
     * @param int             $expiration
     */
    public function __construct(Authenticatable $user, string $role, string $token, int $expiration)
    {
        $this->user       = $user;
        $this->token      = $token;
        $this->expiration = $expiration;
        $this->role       = $role;
    }

    /**
     * @return Authenticatable
     */
    public function getUser(): Authenticatable
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return int
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }
}
