<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GuestBook extends Model
{
    use HasFactory;

    protected $table = 'guests_book';

    protected $primaryKey = 'id';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'visitor_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone_number',
        'visit_type',
        'checkin_time',
        'checkout_time',
        'visit_time_total',
        'created_by',
        'updated_by',
    ];

    public static function loadData($request){
        $data = NULL;
        DB::beginTransaction();
        try {
            $get_data = GuestBook::orderBy('created_at', 'DESC')
                ->when(request()->search['value'], function ($query) {
                    $query->where('visitor_name', 'like', '%' . request()->search['value'] . '%');
                    $query->orWhere('visitor_email', 'like', '%' . request()->search['value'] . '%');
                    $query->orWhere('visitor_phone_number', 'like', '%' . request()->search['value'] . '%');
                })
                ->when(request()->type != null, function ($query) {
                    $query->where('type', request()->type);
                })
                ->when(request()->visitors != null, function ($query) {
                    $query->whereIn('visitor_id', request()->visitors);
                })
                ->when(request()->created_at != null, function ($query) {
                    $created_ranges = explode(' - ', request()->created_at);
                    $query->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($created_ranges[0].' 00:00:00')));
                    $query->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($created_ranges[1].' 23:59:59')));
                })
                ->when(request()->checkin_time != null, function ($query) {
                    $checkin_ranges = explode(' - ', request()->checkin_time);
                    $query->where('checkin_time', '>=', date('Y-m-d H:i:s', strtotime($checkin_ranges[0].' 00:00:00')));
                    $query->where('checkin_time', '<=', date('Y-m-d H:i:s', strtotime($checkin_ranges[1].' 23:59:59')));
                })
                ->when(request()->checkout_time != null, function ($query) {
                    $checkout_ranges = explode(' - ', request()->checkout_time);
                    $query->where('checkout_time', '>=', date('Y-m-d H:i:s', strtotime($checkout_ranges[0].' 00:00:00')));
                    $query->where('checkout_time', '<=', date('Y-m-d H:i:s', strtotime($checkout_ranges[1].' 23:59:59')));
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
    
    public static function getVisitThisDayByVisitor($visitor_id){
        $data = NULL;
        DB::beginTransaction();
        try {
            $this_day = date('Y-m-d');
            $data = GuestBook::where('visitor_id', $visitor_id)
                ->where(function($q){
                    $q->whereDate('checkin_time', today())
                      ->orWhereDate('checkout_time', today());
                })
                // ->when($this_day, function($query) use($this_day){
                //     $query->where('checkin_time', 'like', $this_day.'%');
                //     $query->orWhere('checkout_time', 'like', $this_day.'%');
                // })
                ->get();
    
            Cache::flush();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = $th;
        }
        return $data;

    }
}
