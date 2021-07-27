<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login', 'getUserData', 'getUserProfile']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Email and Password does\'t exist.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->admin());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserData(Request $request)
    {
        $token = $request->bearerToken();
        $user = auth('admin')->authenticate($token);
        if ($user) {
            $allData = User::orderBy('id', 'DESC')->get();

            return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Users Data.',
                    'data' => $allData,
                ]);
        } else {
            return response()->json([
                'status' => 'error',
                'status_code' => 401,
                'message' => 'Invalid token !',
            ]);
        }
    }

    public function getUserProfile(Request $request)
    {
        $token = $request->bearerToken();
        $user = auth('admin')->authenticate($token);
        if ($user) {
            return response()->json([
                    'status' => 'success',
                    'status_code' => 200,
                    'message' => 'Users Data.',
                    'data' => $user,
                ]);
        } else {
            return response()->json([
                    'status' => 'error',
                    'status_code' => 401,
                    'message' => 'Invalid token !',
                ]);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60,
            'admin' => auth(),
        ]);
    }
}
