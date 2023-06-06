<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenController extends Controller
{
    /**
     * Login
     *
     * @param LoginRequest $request
     *
     * @return void
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                return response()->json([
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => 'Error in Login'
                ]);
            }

            //Create token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $userInfo = (new UserResource($user));

            $userInfo['access_token'] = $tokenResult;

            return response()->json([
                'status' => Response::HTTP_OK,
                'access_token' => $tokenResult,
                'user' => $userInfo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Logout
     *
     * @param Request $request
     *
     * @return void
     */
    public function logout(Request $request)
    {
        try {
            // Get bearer token from the request
            $accessToken = $request->bearerToken();

            // Get access token from database
            $token = PersonalAccessToken::findToken($accessToken);

            // Revoke token
            $token->delete();

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                "message" => $e->getMessage()
            ]);
        }
    }
}
