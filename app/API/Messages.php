<?php

namespace App\Api;

class Messages
{
    public static function message(bool $success, $message)
    {
        return ['data' => [
            'success' => $success,
            'message' => $message,
        ]];
    }


}
