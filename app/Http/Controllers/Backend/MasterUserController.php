<?php

namespace App\Http\Controllers\Backend;

use App\Exports\MasterUserExport;
use App\Helpers\CodeHelper;
use App\Helpers\IdGenerator;
use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterUserRequest;
use App\Mail\ActivationRegisterEmail;
use App\Models\CompanyProfile;
use App\Models\User;
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

class MasterUserController extends Controller
{
    public function loadData(Request $request){
        $data = User::loadData($request);

        $response = [
            'success' => true,
            'recordsTotal' => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data' => $data['data'],
        ];
        
        return response()->json($response, Response::HTTP_OK);
    }

    public function export(Request $request){
        return Excel::download(new MasterUserExport($request), 'master-user.xlsx');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.master_data.master_user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.master_data.master_user.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MasterUserRequest $request)
    {
        DB::beginTransaction();
        try{
            $image = null;
            if (!empty($request->file('image'))) {
                $image = time() .'-'. rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
                $destinationPath = public_path('/assets/uploads/user');
                $request->file('image')->move($destinationPath, $image);
            }

            $id = IdGenerator::generate('USR', 'users');

            $activation_code = CodeHelper::generateRandomCode(8);
            $activation_code_encode = CodeHelper::encodeCode($activation_code);

            $email_encode = CodeHelper::encodeCode($request->email);

            $save = User::insert([
                'id' => $id,
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'active' => ($request->active ? 1 : 0),
                'email_verified_at' => ($request->active ? date('Y-m-d H:i:s') : null),
                'activation_code' => $activation_code,
                'image' => $image,
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'created_by' => Auth::guard('web')->user()->id,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $user = User::where('id', $id)->first();
            $url_activation = route('register.activation', ['email' => $email_encode, 'code' => $activation_code_encode]);

            $company_profile = CompanyProfile::first();
            Mail::to($request->email)->send(new ActivationRegisterEmail($user, $url_activation, $company_profile));

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
        $data = User::where('id', $id)->first();
        return view('backend.master_data.master_user.detail', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::where('id', $id)->first();
        return view('backend.master_data.master_user.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MasterUserRequest $request, string $id)
    {
        DB::beginTransaction();
        try{
            $user = User::where('id', $id)->first();
        
            $image = $user->image;
            if (!empty($request->file('image'))) {
                if (File::exists(public_path('assets/uploads/user/' . $user->image))) {
                    File::delete(public_path('assets/uploads/user/' . $user->image));
                }
                $image = time() .'-'. rand(1000, 9999) . '.' . $request->file('image')->getClientOriginalExtension();
                $destinationPath = public_path('/assets/uploads/user');
                $request->file('image')->move($destinationPath, $image);
            }

            $send_email = false;
            if($user->email != $request->email){
                $send_email = true;
                $activation_code = CodeHelper::generateRandomCode(8);
                $activation_code_encode = CodeHelper::encodeCode($activation_code);
                $email_encode = CodeHelper::encodeCode($request->email);

                $url_activation = route('register.activation', ['email' => $email_encode, 'code' => $activation_code_encode]);
            }

            $save = $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'active' => ($request->active ? 1 : 0),
                'email_verified_at' => ($request->active ? date('Y-m-d H:i:s') : null),
                'image' => $image,
                'activation_code' => ($send_email) ? $activation_code : $user->activation_code,
                'updated_by' => Auth::guard('web')->user()->id,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if($send_email){
                $user = User::where('id', $id)->first();
                $company_profile = CompanyProfile::first();
                Mail::to($request->email)->send(new ActivationRegisterEmail($user, $url_activation, $company_profile));
            }

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
            $show = User::where('id', $id)->first();
            if($show->image){
                if (File::exists(public_path('assets/uploads/user/' . $show->image))) {
                    File::delete(public_path('assets/uploads/user/' . $show->image));
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
