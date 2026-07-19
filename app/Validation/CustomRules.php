<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Validates that a password meets complexity requirements:
     * - At least one uppercase letter
     * - At least one lowercase letter
     * - At least one number
     * - At least one special character
     */
    public function strong_password(string $str, ?string &$error = null): bool
    {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/', $str)) {
            $error = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
            return false;
        }

        return true;
    }
}
