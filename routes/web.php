<?php

$router->get('/', function() {
    return response()->json([
        'msg' => 'Welcome to Zomato API Services...🙏'
    ]);
});

// Auth Routes
$router->post('api/auth/login', 'AuthController@login');