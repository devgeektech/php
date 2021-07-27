<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Services\DataService;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public $loginAfterSignUp = true;

    protected $dataService;
    protected $userService;

    public function __construct(DataService $dataService, UserService $userService
    ) {
        $this->dataService = $dataService;

        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $data = $this->dataService->getData($request);

        $validator = Validator::make(collect($data)->toArray(), [
            'name' => 'required|string',

            'email' => 'required|email|unique:users',

            'password' => 'required|min:6|max:20',
        ]);

        if ($validator->fails()) {
            $responseObject = [
                'status' => 400,

                'message' => $validator->errors(),

                'data' => null,
            ];

            return response()->json($responseObject, 200);
        } else {
            $user = new User();

            $user->name = $request->name;

            $user->email = $request->email;

            $user->password = bcrypt($request->password);

            $user->save();

            if ($this->loginAfterSignUp) {
                return $this->login($request);
            }

            return response()->json([
            'success' => true,

            'data' => $user,
        ], 200);
        }
    }

    public function login(Request $request)
    {
        $data = $this->dataService->getData($request);

        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt(collect($data)->only(['email', 'password'])->toArray())) {
            return response()->json([
                'success' => false,

                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,

            'token' => $jwt_token,
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,

                'message' => 'User logged out successfully',
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,

                'message' => 'Sorry, the user cannot be logged out',
            ], 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        $token = $request->bearerToken();

        $auth = $this->userService->verifyJwtRequest($token);

        if ($auth['status']) {
            $result = [
                'status' => 200,

                'data' => $auth,
            ];

            $userId = $auth['user']->userId;
        } else {
            $result = [
                'status' => 401,

                'message' => 'Invalid token',
            ];
        }

        return response()->json($result, 200);
    }
}
