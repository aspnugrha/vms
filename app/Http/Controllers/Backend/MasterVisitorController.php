<?php

namespace App\Http\Controllers\Backend;

use App\Exports\MasterVisitorExport;
use App\Helpers\CodeHelper;
use App\Helpers\IdGenerator;
use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterVisitorRequest;
use App\Models\GuestBook;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MasterVisitorController extends Controller
{
    public function loadData(Request $request){
        $data = Visitor::loadData($request);

        $response = [
            'success' => true,
            'recordsTotal' => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data' => $data['data'],
        ];
        
        return response()->json($response, Response::HTTP_OK);
    }

    public function export(Request $request){
        return Excel::download(new MasterVisitorExport($request), 'master-visitor.xlsx');
    }
    
    public function printCard($code)
    {
        $data = Visitor::where('code', $code)->first();
        return view('backend.master_data.master_visitor.print', compact('data'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.master_data.master_visitor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.master_data.master_visitor.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MasterVisitorRequest $request)
    {
        DB::beginTransaction();
        try{
            $image = null;
            if (!empty($request->file('image'))) {
                $image = time() .'-'. rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
                $destinationPath = public_path('/assets/uploads/visitor');
                $request->file('image')->move($destinationPath, $image);
            }

            $id = IdGenerator::generate('VSTR', 'visitors');

            $save = Visitor::insert([
                'id' => $id,
                'code' => 'VST-'.time().'-'.CodeHelper::generateRandomCodeCapital(6),
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'active' => ($request->active ? 1 : 0),
                'image' => $image,
                'created_by' => Auth::guard('web')->user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Cache::flush();
            DB::commit();

            return response()->json([
                'response' => $save,
                'success' => true,
                'status' => 'success',
            ]);
        } catch (\Exception $exc) {
            DB::rollBack();
            return $exc;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Visitor::where('id', $id)->first();
        $data_visit = GuestBook::getVisitThisDayByVisitor($id);
        
        return view('backend.master_data.master_visitor.detail', compact('data', 'data_visit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Visitor::where('id', $id)->first();
        return view('backend.master_data.master_visitor.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MasterVisitorRequest $request, string $id)
    {
        DB::beginTransaction();
        try{
            $visitor = Visitor::where('id', $id)->first();
        
            $image = $visitor->image;
            if (!empty($request->file('image'))) {
                if (File::exists(public_path('assets/uploads/visitor/' . $visitor->image))) {
                    File::delete(public_path('assets/uploads/visitor/' . $visitor->image));
                }
                $image = time() .'-'. rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
                $destinationPath = public_path('/assets/uploads/visitor');
                $request->file('image')->move($destinationPath, $image);
            }

            $save = $visitor->update([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'active' => ($request->active ? 1 : 0),
                'image' => $image,
                'updated_by' => Auth::guard('web')->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Cache::flush();
            DB::commit();

            return response()->json([
                'response' => $save,
                'success' => true,
                'status' => 'success',
            ]);
        } catch (\Exception $exc) {
            DB::rollBack();
            return $exc;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
            $show = Visitor::where('id', $id)->first();
            if($show->image){
                if (File::exists(public_path('assets/uploads/visitor/' . $show->image))) {
                    File::delete(public_path('assets/uploads/visitor/' . $show->image));
                }
            }
            $delete = $show->delete();

            Cache::flush();
            DB::commit();

            return response()->json([
                'response' => $delete,
                'success' => true,
                'status' => 'success',
            ]);
        } catch (\Exception $exc) {
            DB::rollBack();
            return $exc;
        }
    }
}
