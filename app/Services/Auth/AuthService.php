<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;

abstract class AuthService
{
    /**
     * @param Request $request
     *
     * @return AuthenticatedUser|null
     */
    public function authenticate(Request $request): ?AuthenticatedUser
    {
        if ($authenticatedUser = $this->attempt($request)) {
            return $this->onSuccess($authenticatedUser);
        }

        return $this->onFail();
    }

    /**
     * @param Request $request
     *
     * @return AuthenticatedUser|null
     */
    abstract protected function attempt(Request $request): ?AuthenticatedUser;

    /**
     * @param AuthenticatedUser $authenticatedUser
     *
     * @return AuthenticatedUser
     */
    abstract protected function onSuccess(AuthenticatedUser $authenticatedUser): AuthenticatedUser;

    /**
     * @return mixed
     */
    abstract protected function onFail(): mixed;
}
