<?php

namespace App\Http\Controllers\Backend;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
class UsersController extends Controller
{
    public function users_index(Request $request)
    {
        $comments = [];
        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
        $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        $users = User::query();
       
        if($keyword != null){
            $users = $users->where('name' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('username' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('email' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('mobile' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('bio' , 'LIKE' , "%{$request->keyword}%");

        }
        
        if($status != null){

            $users = $users->where('status' , 'LIKE' , "%{$request->status}%");
        }

        $users = $users->orderBy($sort_by, $order_by);
        $users = $users->paginate($limit_by);

        return view('backend.users.index', compact('users', 'comments'));
    }
    public function users_create()
    {
        $comments = [];
        return view('backend.users.create', compact('comments'));
    }
    public function users_store(Request $request)
    {
        $user_name = $request->name;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'username' => 'required|max:20|unique:users',
            'email' => 'required|email|mak:255|unique:users',
            'mobile' => 'required|numeric|unique:users',
            'status' => 'required',
            'password' => 'required|min:8',
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data['name'] = $request->name;

        $data['username'] = $request->username;

        $data['email'] = $request->email;
        $data['email_verified_at'] = Carbon::now();
        $data['mobile'] = $request->mobile;

        $data['status'] = $request->status;

        $data['password'] = bcrypt($request->password);

        $data['bio'] = $request->bio;
        $data['recieve_email'] = $request->recieve_email;
       
       if ($user_image = $request->file('user_image') ) {

                $filename = $user_name->name.'.'.$user_image->getClientOriginalExtension();
                $path = public_path('assets/users/' . $filename);
                Image::make($user_image->getRealPath())->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);

                $data['user_image'] = $filename;
        }
        $user = User::create($data);

        if ($request->status == 1) {
            Cache::forget('recent_users');
        }

        return redirect('admin/users_index')->with([
            'message' => 'User created successfully',
            'alert-type' => 'success',
        ]);

    }
    public function users_show($id)
    {
        $user = User::where('id', $id)->withCount('posts')->first();
        if($user){
            return view('backend.users.show', compact('user'));
        }else{
            return redirect('admin/users_index')->with([
                'message' => 'User created successfully',
            ]);
        }
        
    }
    public function users_edit($id){
        $comments = [];
        $user = User::where('id', $id)->first();
        if($user){
            return view('backend.users.edit', compact('user', 'comments'));
        }else{
            return redirect('admin/users_edit')->with([
                'message' => 'User created successfully',
            ]);
        }
    }
    public function users_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'username'      => 'required|max:20|unique:users,username,'.$id,
            'email'         => 'required|email|max:255|unique:users,email,'.$id,
            'mobile'        => 'required|numeric|unique:users,mobile,'.$id,
            'status'        => 'required',
            'password'      => 'nullable|min:8',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::whereId($id)->first();

        if ($user) {
            $data['name']           = $request->name;
            $data['username']       = $request->username;
            $data['email']          = $request->email;
            $data['mobile']         = $request->mobile;
            if (trim($request->password) != '') {
                $data['password'] = bcrypt($request->password);
            }
            $data['status']         = $request->status;
            $data['bio']            = $request->bio;
            

            if ($user_image = $request->file('user_image')) {
                if ($user->user_image != '') {
                    if (File::exists('assets/users/' . $user->user_image)) {
                        unlink('assets/users/' . $user->user_image);
                    }
                }
                $filename = $request->name.'.'.$user_image->getClientOriginalExtension();
                $path = public_path('assets/users/' . $filename);
                Image::make($user_image->getRealPath())->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);
                $data['user_image']  = $filename;
            }

            $user->update($data);

            return redirect('admin/users_index')->with([
                'message' => 'User updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect('admin/users_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
       
    }
    public function user_media_destroy(Request $request){

        $user = User::whereId($request->user_id)->first();
        if ($user) {
            if (File::exists('assets/users/' . $user->user_image)) {
                unlink('assets/users/' . $user->user_image);
            }
            $user->user_image = null;
            $user->save();
            return 'true';
        }
        return 'false';
    }

    public function users_destroy($id)
    {
        $user = User::whereId($id)->first();

        if ($user) {
            if ($user->user_image != '') {
                
                if (File::exists('assets/users/' . $user->user_image)) {
                   
                    unlink('assets/users/' . $user->user_image);
                }
            }
            
            $user->delete();
            return redirect('admin/users_index')->with([
                'message' => 'User deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect('admin/users_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
}
