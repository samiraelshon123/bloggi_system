<?php

namespace App\Http\Controllers\Backend\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Auth;
use App\Http\Models;
class AdminLoginController extends Controller
{

    public function login()
    {
        return view('backend.auth.login');
    }
    public function loginAdmin(Request $request){

        $this->validate($request,[
            'username'=>'required',
            'password'=>'required|min:6'
        ]);

        if(Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password])){

            return redirect('admin/dashboard');

        }else{

            return back();
        }

    }
    public function index(){
        $comments = [];
        $comments = AdminNotification::all();
        session()->put('notification', count($comments));
        return view('backend.index', compact('comments'));

    }
}
