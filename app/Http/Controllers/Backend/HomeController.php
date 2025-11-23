<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\IdGenerator;
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\GuestBook;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        return view('backend.home.index');
    }

    public function scanQRProcess(Request $request){
        $visitor = Visitor::where('code', $request->visitor_code)->first();

        if($visitor){
            if($visitor->active){
                $time = date('Y-m-d H:i:s');
                $guest_book = GuestBook::where('created_at', 'like', date('Y-m-d').'%')
                                        ->where('visitor_id', $visitor->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                $type = 'IN';
                if($guest_book){
                    $type = ($guest_book->visit_type == 'IN' ? 'OUT' : 'IN');
                }

                $seconds = null;
                if($type == 'OUT'){
                    $date1 = Carbon::parse($guest_book->checkin_time);
                    $date2 = Carbon::parse($time);

                    $seconds = $date1->diffInSeconds($date2);
                }

                GuestBook::insert([
                    'id' => IdGenerator::generate('GSBK', 'guests_book'),
                    'visitor_id' => $visitor->id,
                    'visitor_name' => $visitor->name,
                    'visitor_email' => $visitor->email,
                    'visitor_phone_number' => $visitor->phone_number,
                    'visit_type' => $type,
                    'checkin_time' => ($type == 'IN' ? $time : null),
                    'checkout_time' => ($type == 'OUT' ? $time : null),
                    'visit_time_total' => ($type == 'OUT' ? $seconds : null),
                    'created_at' => $time,
                ]);

                $visitor->update([
                    'total_checkin' => ($type == 'IN' ? (int)$visitor->total_checkin + 1 : (int)$visitor->total_checkin),
                    'total_checkout' => ($type == 'OUT' ? (int)$visitor->total_checkout + 1 : (int)$visitor->total_checkout),
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'success' => true,
                    'message' => 'Visitor dengan kode "'.$visitor->code.'" berhasil check'.strtolower($type).'!',
                ]);
            }else{
                return response()->json([
                    'status' => 'not-active',
                    'success' => false,
                    'message' => 'Visitor dengan kode "'.$visitor->code.'" tidak aktif! Silahkan aktivasi visitor kepada admin.',
                ]);
            }
        }else{
            return response()->json([
                'status' => 'not-found',
                'success' => false,
                'message' => 'Visitor dengan kode "'.$visitor->code.'" tidak ditemukan!',
            ]);
        }
    }

    public function contactUs(){
        $company = CompanyProfile::first();
        
        return view('frontend.contact.index', compact('company'));
    }
}
