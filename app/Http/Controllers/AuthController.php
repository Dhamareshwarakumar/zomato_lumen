<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller {
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /*
    * @route   :: POST /api/auth/login
    * @desc    :: User login
    * @access  :: Public
    */

    public function login(Request $request) {
        $result = $this->authService->login($request->json()->get('email'), $request->json()->get('password'));

        if (!$result['status']) {
            return response()->json(['msg' => $result['msg'], 'err' => $result['errors']], $result['code']);
        }

        return response()->json(['msg' => $result['msg'], 'token' => $result['token']], $result['code']);
    }
}