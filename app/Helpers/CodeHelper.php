<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class CodeHelper
{
    public static function generateRandomCode($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
    public static function generateRandomCodeCapital($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
    public static function encodeCode($code)
    {
        // Gunakan Laravel Crypt agar aman (AES-256)
        return Crypt::encryptString($code);
    }
    public static function decodeCode($encoded)
    {
        try {
            return Crypt::decryptString($encoded);
        } catch (\Exception $e) {
            return null; // Kalau gagal decode, return null
        }
    }
}
