<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApiProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/login',
        summary: 'Login pengguna',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Login berhasil'),
                        new OA\Property(property: 'access_token', type: 'string', example: '1|sanctum-token'),
                        new OA\Property(property: 'user', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Kredensial tidak valid'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function login(Request $request)
    {
        $request->merge([
            'email' => is_string($request->input('email')) ? trim($request->input('email')) : $request->input('email'),
            'password' => is_string($request->input('password')) ? trim($request->input('password')) : $request->input('password'),
        ]);

        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Kredensial tidak valid.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'access_token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'customer') {
            $user->load('customer');
        }
        return response()->json(['user' => $user]);
    }

    public function updateProfile(UpdateApiProfileRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if ($user->role === 'customer') {
            $user->customer()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                ]
            );
        }

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => $user->fresh()->load('customer'),
        ]);
    }
}
