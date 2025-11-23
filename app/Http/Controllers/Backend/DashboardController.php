<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        return view('backend.dashboard.index');
    }
    
    public function loadData(Request $request){
        // total visitor
        $total_visitor_this_day = GuestBook::where('created_at', 'like', date('Y-m-d').'%')->distinct('visitor_id')->count('visitor_id');
        $total_visitor_in_this_day = GuestBook::where('created_at', 'like', date('Y-m-d').'%')->where('visit_type', 'IN')->distinct('visitor_id')->count('visitor_id');
        $total_visitor_out_this_day = GuestBook::where('created_at', 'like', date('Y-m-d').'%')->where('visit_type', 'OUT')->distinct('visitor_id')->count('visitor_id');
        
        // 10 newest visitor
        $newest_visitors = GuestBook::where('created_at', 'like', date('Y-m-d').'%')->orderBy('created_at', 'desc')->limit(10)->get();

        // chart
        $chart_label = [];
        $chart_data = [];

        $currentHour = date('G'); // Format 0-23 tanpa leading zero
        $startHour = 7;

        $hours = [];

        for ($i = $startHour; $i <= $currentHour; $i++) {
            $hours[] = $i;
        }

        if($hours){
            foreach($hours as $jam){
                $chart_label[] = "Jam ".str_pad($jam, 2, '0', STR_PAD_LEFT);
                $chart_data[] = GuestBook::where('created_at', 'like', date('Y-m-d').' '.str_pad($jam, 2, '0', STR_PAD_LEFT).'%')->distinct('visitor_id')->count('visitor_id');
            }
        }
        
        $data = [
            'total_visitor' => $total_visitor_this_day,
            'total_visitor_in' => $total_visitor_in_this_day,
            'total_visitor_out' => $total_visitor_out_this_day,
            'newest_visitors' => $newest_visitors,
            'chart_label' => $chart_label,
            'chart_data' => $chart_data,
        ];

        return response()->json($data);
    }
}
