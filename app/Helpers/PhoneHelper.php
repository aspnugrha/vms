<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PhoneHelper
{
    /**
     * Generate random ID string.
     *
     * @param int $length
     * @return string
     */
    public static function normalize_phone($phone)
    {
        // Hapus semua karakter non-angka
        $phone = preg_replace('/\D/', '', $phone);

        // Jika diawali 0 -> ubah jadi 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika sudah ada 62 di depan, biarkan
        elseif (substr($phone, 0, 2) === '62') {
            // tidak diubah
        }

        // Jika diawali 8 (tanpa 0/62), tambahkan 62 di depan
        elseif (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }

        // Jika diawali +62, hapus + di depan
        elseif (substr($phone, 0, 3) === '+62') {
            $phone = substr($phone, 1);
        }

        return $phone;
    }
}
