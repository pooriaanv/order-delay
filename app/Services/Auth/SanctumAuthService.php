<?php

namespace App\Services\Auth;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SanctumAuthService extends AuthService
{
    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     *
     * @return AuthenticatedUser|null
     */
    public function attempt(Request $request): ?AuthenticatedUser
    {
        $user = $this->getUser($request->get('email'));

        if (!$user || !Hash::check($request->get('password'), $user->password)) {
            return null;
        }

        return $this->createAuthenticatedUser($user);
    }

    /**
     * @param AuthenticatedUser $authenticatedUser
     *
     * @return AuthenticatedUser
     */
    public function onSuccess(AuthenticatedUser $authenticatedUser): AuthenticatedUser
    {
        return $authenticatedUser;
    }

    /**
     * @return mixed
     */
    public function onFail(): mixed
    {
        return null;
    }

    /**
     * @param string $email
     *
     * @return Model|null
     */
    private function getUser(string $email): ?Authenticatable
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * @param Authenticatable $authenticatable
     *
     * @return AuthenticatedUser
     */
    private function createAuthenticatedUser(Authenticatable $authenticatable): AuthenticatedUser
    {
        return new AuthenticatedUser(
            $authenticatable,
            $authenticatable->role,
            $authenticatable->createToken('api')->plainTextToken,
            now()->addSeconds(config('sanctum.expiration'))->timestamp
        );
    }
}
