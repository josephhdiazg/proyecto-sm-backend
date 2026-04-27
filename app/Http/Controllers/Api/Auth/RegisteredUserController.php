<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'enterprise_code' => 'required|string|max:8',
        ]);

        $enterprise = Enterprise::all()->firstWhere('code', $request->enterprise_code);

        if (! $enterprise) {
            return response(status: 422)->json([
                'message' => 'No se ha encontrado empresa con código: '.$request->enterprise_code
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'enterprise_id' => $enterprise->id
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'message' => 'Usuario Creado Correctamente'
        ]);
    }
}
