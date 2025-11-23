<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CodeHelper;
use App\Helpers\IdGenerator;
use App\Http\Controllers\Controller;
use App\Mail\ActivationRegisterEmail;
use App\Mail\ChangePasswordEmail;
use App\Mail\ForgotPasswordEmail;
use App\Models\CompanyProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(){
        return view('backend.auth.login');
        
        // $customer = Customers::first();
        // $company_profile = CompanyProfile::first();
        // $url = '';
        // return view('backend.email.register', compact('customer', 'company_profile', 'url'));
    }

    // public function register(){
    //     return view('backend.auth.register');
    // }
    
    public function activation(){
        return view('backend.auth.activation');
    }
    
    public function forgotPassword(){
        return view('backend.auth.forgot-password');
    }

    public function loginProcess(Request $request){
        $cek_email = User::where('email', $request->email)->first();
        
        if(!$cek_email){
            return response()->json([
                'status' => 'not-found',
                'success' => false,
                'message' => 'Account with email '.$request->email.' is not registered yet.',
            ]);
        }else{
            if(!$cek_email->active){
                return response()->json([
                    'status' => 'not-active',
                    'success' => false,
                    'message' => 'Your account is not active yet, please activate it first.',
                ]);
            }
        }

        
        $credentials = $request->only('email', 'password');
        // dd($cek_email, $credentials);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'Login successful.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'success' => false,
            'message' => 'Incorrect email or password.',
        ]);
    }

    // public function registerProcess(Request $request){
    //     DB::beginTransaction();
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'name' => 'required',
    //             'email' => 'required|email|unique:customers,email',
    //             'phone_number' => 'unique:customers,phone_number',
    //             'password'          => 'required|min:6',
    //         ]
    //         ,[
    //             'name.required'     => 'Nama is required.',
    //             'email.required'    => 'Email is required.',
    //             'email.email'       => 'Email not valid.',
    //             'email.unique'      => 'Email already used.',
    //             'phone_number.unique'  => 'Phone Number already used.',
    //             'password.required'    => 'Password is required.',
    //             'password.min'      => 'Password must contain at least 6 characters.',
    //         ]
    //     );
    //     // dd($validator->getMessageBag());
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 'validation',
    //                 'success' => false,
    //                 'message' => 'Sorry something went wrong.',
    //                 'errors' => $validator->errors(),
    //             ]);
    //         }

    //         $activation_code = CodeHelper::generateRandomCode(8);
    //         $activation_code_encode = CodeHelper::encodeCode($activation_code);

    //         $email_encode = CodeHelper::encodeCode($request->email);

    //         $phone_number = $request->phone_number;

    //         if($phone_number){
    //             $get_2_phone = substr($phone_number, 0, 2);

    //             if ($get_2_phone == '08') $phone_number = '628'.substr($phone_number, 2, strlen($phone_number));
    //         }
    //         // dd($phone_number);

    //         $id = IdGenerator::generate('CTMR', 'customers');
    //         Customers::insert([
    //             'id' => $id,
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'phone_number' => $phone_number,
    //             'password' => password_hash($request->password, PASSWORD_DEFAULT),
    //             'active' => 0,
    //             'activation_code' => $activation_code,
    //             'created_at' => date('Y-m-d H:i:s'),
    //         ]);

    //         $customer = Customers::where('id', $id)->first();
    //         $url_activation = route('register.activation', ['email' => $email_encode, 'code' => $activation_code_encode]);

    //         $company_profile = CompanyProfile::first();
    //         Mail::to($request->email)->send(new ActivationRegisterEmail($customer, $url_activation, $company_profile));

    //         DB::commit();
    
    //         return response()->json([
    //             'status' => 'success',
    //             'success' => true,
    //             'message' => 'Register successful.',
    //         ]);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return response()->json([
    //             'response' => $th->getMessage(),
    //             'status' => 'error',
    //             'success' => false,
    //         ]);
    //     }
    // }

    public function activationProcess(Request $request){
        DB::beginTransaction();
        try {
            $activation_code = CodeHelper::generateRandomCode(8);
            $activation_code_encode = CodeHelper::encodeCode($activation_code);

            $email_encode = CodeHelper::encodeCode($request->email);
            $customer = User::where('email', $request->email)->first();

            if(!$customer){
                return response()->json([
                    'status' => 'email',
                    'success' => false,
                    'message' => 'Account with email '.$request->email.' is not registered yet.',
                ]);
            }

            $customer->update([
                'activation_code' => $activation_code,
            ]);

            $url_activation = route('register.activation', ['email' => $email_encode, 'code' => $activation_code_encode]);

            $company_profile = CompanyProfile::first();
            Mail::to($request->email)->send(new ActivationRegisterEmail($customer, $url_activation, $company_profile));

            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'The activation link has been successfully sent to your email.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'response' => $th->getMessage(),
                'status' => 'error',
                'success' => false,
            ]);
        }
    }

    public function forgotPasswordProcess(Request $request){
        DB::beginTransaction();
        try {
            $activation_code = CodeHelper::generateRandomCode(8);
            $activation_code_encode = CodeHelper::encodeCode($activation_code);

            $email_encode = CodeHelper::encodeCode($request->email);
            $customer = User::where('email', $request->email)->first();

            if(!$customer){
                return response()->json([
                    'status' => 'email',
                    'success' => false,
                    'message' => 'Account with email '.$request->email.' is not registered yet.',
                ]);
            }

            $customer->update([
                'activation_code' => $activation_code,
            ]);

            $url = route('set-new-password', ['email' => $email_encode, 'code' => $activation_code_encode]);

            $company_profile = CompanyProfile::first();
            Mail::to($request->email)->send(new ForgotPasswordEmail($customer, $url, $company_profile));

            DB::commit();
    
            return response()->json([
                'status' => 'success',
                'success' => true,
                'message' => 'The email has been successfully sent to your email.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'response' => $th->getMessage(),
                'status' => 'error',
                'success' => false,
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }

    public function registerActivationProcess($email, $code){
        $email = CodeHelper::decodeCode($email);
        $code = CodeHelper::decodeCode($code);
        
        $cek_email = User::where('email', $email)->first();
        if($cek_email){
            if($cek_email->activation_code == $code){
                $cek_email->update([
                    'active' => 1,
                    'email_verified_at' => date("Y-m-d H:i:s"),
                    // 'activation_code' => null,
                ]);

                $data = [
                    'status' => 'success',
                    'success' => true,
                    'message' => 'Great! Your account is now active. Please log in to enjoy our web features.',
                ];
            }else{
                // if($cek_email->active){
                //     $data = [
                //         'status' => 'active',
                //         'success' => true,
                //         'message' => 'Great! Your account is now active. Please log in to enjoy our web features.',
                //     ];
                // }else{
                // }
                $data = [
                    'status' => 'activation-code',
                    'success' => false,
                    'message' => 'Sorry, your account activation code is incorrect! Please reactivate it using the link below.',
                ];
            }
        }else{
            $data = [
                'status' => 'email',
                'success' => false,
                'message' => 'Sorry, your email address '.$email.' was not found! Please re-register using the link below.',
            ];
        }

        // dd($data, $email, $code);

        return view('backend.auth.activation-result', compact('data'));
    }

    public function setNewPassword($email, $code){
        $email = CodeHelper::decodeCode($email);
        $code = CodeHelper::decodeCode($code);
        
        $cek_email = User::where('email', $email)->first();
        if($cek_email){
            if($cek_email->activation_code == $code){
                $data = [
                    'status' => 'success',
                    'success' => true,
                    'data' => [
                        'email' => $email,
                        'code' => $code,
                    ],
                    'message' => 'Great, one more step! Set up your account by creating a new password.',
                ];
            }else{
                $data = [
                    'status' => 'activation-code',
                    'success' => false,
                    'message' => 'Sorry, something went wrong. Please repeat the process of Forgot Password or contact our admin.',
                ];
            }
        }else{
            $data = [
                'status' => 'email',
                'success' => false,
                'message' => 'Sorry, your email address '.$email.' was not found! Please repeat the process of Forgot Password using the link below.',
            ];
        }

        // dd($data, $email, $code);

        return view('backend.auth.forgot-password-result', compact('data'));
    }

    public function setNewPasswordProcess(Request $request){
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'password'          => 'required|min:6',
                'confirm_password'  => 'required|min:6|same:password',
            ]
            ,[
                'password.required'         => 'Password is required.',
                'password.min'              => 'Password must contain at least 6 characters.',
                'confirm_password.required' => 'Password is required.',
                'confirm_password.min'      => 'Password must contain at least 6 characters.',
                'confirm_password.same'     => 'Password must be the same as Password.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'validation',
                    'success' => false,
                    'message' => 'Sorry something went wrong.',
                    'errors' => $validator->errors(),
                ]);
            }

            $cek_email = User::where('email', $request->email)->first();
            if($cek_email){
                if($cek_email->activation_code == $request->code){
                    $cek_email->update([
                        'password' => password_hash($request->password, PASSWORD_DEFAULT),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    $company_profile = CompanyProfile::first();
                    Mail::to($request->email)->send(new ChangePasswordEmail($cek_email, $company_profile));
                    
                    DB::commit();

                    return response()->json([
                        'status' => 'success',
                        'success' => true,
                        'data' => [
                            'email' => $request->email,
                            'code' => $request->code,
                        ],
                        'message' => 'Great, one more step! Set up your account by creating a new password.',
                    ]);
                }else{
                    return response()->json([
                        'status' => 'activation-code',
                        'success' => false,
                        'message' => 'Sorry, something went wrong. Please repeat the process of Forgot Password or contact our admin.',
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'email',
                    'success' => false,
                    'message' => 'Sorry, your email address '.$request->email.' was not found! Please repeat the process of Forgot Password using the link below.',
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'response' => $th->getMessage(),
                'status' => 'error',
                'success' => false,
            ]);
        }
    }
}
