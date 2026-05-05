<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // $token = $request->authenticateApi();

        if (! Auth::guard('api')->validate($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $user = User::firstWhere('email', $request->input('email'));
        $token = Auth::guard('api')->login($user);

        return response()->json([
            'message' => 'Sesion autenticada exitosamente.',
            'token' => $token,
            'token_expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Sesion cerrada exitosamente.'
        ]);
    }
}
