<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService)
    {
    }

    public function login(): \Illuminate\Http\JsonResponse
    {
        return $this->authService->login();
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        return $this->authService->logout();
    }

    public function refresh(): \Illuminate\Http\JsonResponse
    {
        return $this->authService->refresh();
    }
}
