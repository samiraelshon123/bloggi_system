<?php

namespace App\Http\Controllers\Frontend\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Intervention\Image\Facades\Image;
class RegisterController extends Controller
{
    public function register(){
        return view('frontend.auth.register');
    }
    public function registerUser(Request $request){
        $data = $this->validate(request(),[
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'


        ],[],[]);
        if($request->hasFile('user_image')){
            $file = $request->file('user_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;


        }
        if ($user_image = $request->file('user_image') ) {

            $filename = $request->name.'.'.$user_image->getClientOriginalExtension();
            $path = public_path('assets/users/' . $filename);
            Image::make($user_image->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            
    }
        $news = User::create( [

            'name' => request('name'),
            'username' => request('username'),
            'email' => request('email'),
            'mobile' => request('mobile'),
            'password' => bcrypt(request('password')),
            'user_image' => $filename,

        ]);
        return redirect('user/dashboard')->with([
            'message' => 'Your account registered successfully.',
            'alert-type' => 'success'
        ]);
    }


}
