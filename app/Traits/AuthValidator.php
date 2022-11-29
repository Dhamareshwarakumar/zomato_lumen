<?php

namespace App\Traits;


trait AuthValidator {
    public function validateLogin($email, $password) {
        $errors = [];

        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid Email';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } else if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        return $errors;
    }
}