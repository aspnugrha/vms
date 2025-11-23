<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'code',
        'name',
        'email',
        'phone_number',
        'image',
        'email_verified_at',
        'active',
        'total_checkin',
        'total_checkout',
        'created_by',
        'updated_by',
    ];

    public static function loadData($request){
        $data = NULL;
        DB::beginTransaction();
        try {
            $get_data = Visitor::orderBy('created_at', 'DESC')
                ->when(request()->search['value'], function ($query) {
                    $query->where('name', 'like', '%' . request()->search['value'] . '%');
                    $query->orWhere('email', 'like', '%' . request()->search['value'] . '%');
                    $query->orWhere('phone_number', 'like', '%' . request()->search['value'] . '%');
                })
                ->when(request()->active != null, function ($query) {
                    $query->where('active', request()->active);
                })
                ->when(request()->created_at != null, function ($query) {
                    $created_ranges = explode(' - ', request()->created_at);
                    $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($created_ranges[0].' 00:00:00')));
                    $query->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($created_ranges[1].' 23:59:59')));
                });

            $data = [
                'recordsTotal' => $get_data->count(),
                'recordsFiltered' => $get_data->count(),
                'data' => $get_data->skip($request->input('start'))->take($request->input('length'))->get()
            ];

            Cache::flush();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
        }
        return $data;
    }
}
