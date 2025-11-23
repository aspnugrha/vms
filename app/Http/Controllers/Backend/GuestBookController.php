<?php

namespace App\Http\Controllers\Backend;

use App\Exports\GuestBookExport;
use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class GuestBookController extends Controller
{
    public function loadData(Request $request){
        $data = GuestBook::loadData($request);

        $response = [
            'success' => true,
            'recordsTotal' => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data' => $data['data'],
        ];
        
        return response()->json($response, Response::HTTP_OK);
    }

    public function export(Request $request){
        return Excel::download(new GuestBookExport($request), 'guestbook.xlsx');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visitors = Visitor::select('id', 'name', 'code', 'email')->where('active', 1)->get();
        return view('backend.guestbook.index', compact('visitors'));
    }
}
