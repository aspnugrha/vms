<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class IdGenerator
{
    /**
     * Generate random ID string.
     *
     * @param int $length
     * @return string
     */
    public static function generate($code, $table)
    {
        $get = DB::table($table)->where('id', 'like', $code.date('Ymd').'%')->max('id');
        $maxId = 0;
        if($get){
            $maxId = (int)substr($get, (strlen($code) + 9), 5);
        }
        $next = $maxId ? intval($maxId) + 1 : 1;
        
        $start_code = str_pad($next, 5, '0', STR_PAD_LEFT);

        // dd($get, $maxId, $next, $start_code);

        // karakter yang aman untuk ID
        // $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        // $randomString = '';
        // for ($i = 0; $i < 6; $i++) {
        //     $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        // }
        
        return $code.date('Ymd').$start_code;
        // .$randomString;
    }
}
