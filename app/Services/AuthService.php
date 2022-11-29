<?php

namespace App\Services;

use Firebase\JWT\JWT;
use App\Traits\AuthValidator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthService {
    use AuthValidator;

    public function login($email, $password) {
        // Validate Inputs
        $errors = $this->validateLogin($email, $password);

        if (!empty($errors)) {
            return [
                'status' => false,
                'msg' => 'Validation Error',
                'errors' => $errors,
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ];
        }

        // Check if user exists
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            $userResult = $this->register($email, $password);

            if (!$userResult['status']) {
                return [
                    'status' => false,
                    'msg' => $userResult['msg'],
                    'errors' => $userResult['errors'],
                    'code' => $userResult['code']
                ];
            }
            $user = $userResult['user'];
        }

        // Check if password matches
        if(!Hash::check($password, $user->password)) {
            return [
                'status' => false,
                'msg' => 'Invalid Credentials',
                'errors' => ['password' => 'Invalid Password'],
                'code' => Response::HTTP_UNAUTHORIZED
            ];
        }

        // Generate Token
        $payload = [
            'user_id' => $user->user_id,
            'email' => $user->email,
            'role' => $user->role,
            'iat' => time(),
            'exp' => time() + (60 * 60)
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return [
            'status' => true,
            'msg' => 'Login Successful',
            'token' => $token,
            'code' => Response::HTTP_OK
        ];
    }

    public function register($email, $password) {
        // Create User
        DB::beginTransaction();
        $result = DB::table('users')->insert([
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        if (!$result) {
            return [
                'status' => false,
                'msg' => 'Something went wrong',
                'errors' => [],
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return [
                'status' => false,
                'msg' => 'Something went wrong',
                'errors' => [],
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }

        DB::commit();

        return [
            'status' => true,
            'msg' => 'User created successfully',
            'user' => $user,
            'code' => Response::HTTP_CREATED
        ];
    }
}