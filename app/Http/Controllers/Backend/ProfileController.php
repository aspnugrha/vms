<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\IdGenerator;
use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(){
        return view('backend.profile.index');
    }
}
