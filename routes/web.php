<?php

$router->get('/', function() {
    return response()->json([
        'msg' => 'Welcome to Zomato API Services...🙏'
    ]);
});