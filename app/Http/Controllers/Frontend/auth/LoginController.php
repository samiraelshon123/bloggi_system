<?php

namespace App\Http\Controllers\Frontend\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{

    public function login(){
        return view('frontend.auth.login');
    }
    public function loginUser(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);

        if(Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])){

            return redirect('user/dashboard')->with([
                'message' => 'Logged in successfully.',
                'alert-type' => 'success'
            ]);

        }else{

            return back()->with([
                'message' => 'You are not logged, please try again.',
                'alert-type' => 'danger'
            ]);
        }
    }
}
