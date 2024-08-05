<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Exception;

class UserController extends Controller implements \Illuminate\Routing\Controllers\HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:api', except:['login', 'register']),
        ];
    }

    public function login(): JsonResponse
    {
        try{
            $credentials = request(['email', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(
                    ['error' => 'Unauthorized'
                ], 401);
            }
    
            return $this->respondWithToken($token);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function me(): JsonResponse
    {
        try{
            return response()->json(auth()->user());
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        try{
            auth()->logout();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function refresh(): JsonResponse
    {
        try{
            return $this->respondWithToken(auth()->refresh());
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 401);
        }
    }

    public function register(Request $request){
        try{

            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|string|same:password',
            ]);

            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            return response()->json([
                'message' => 'User created successfully',
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 401);
        }
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}