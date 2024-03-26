<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private AuthService $authService;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $authenticatedUser = $this->authService->authenticate($request);
        if (!$authenticatedUser) {
            return fail(__('provided credentials is not valid'), __('provided credentials is not valid'), 401);
        }

        return success([
            'access_token' => $authenticatedUser->getToken(),
            'expire_time'  => $authenticatedUser->getExpiration()
        ], __('success'));
    }
}
