<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\PostMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
class UserController extends Controller
{
    public function index(){


        $posts = auth()->user()->posts()->with(['media', 'category', 'user'])->withCount('comments')->orderBy('id', 'desc')->paginate(10);
        $comments = Notification::all();
        $count = 0;

        session()->put('user_notification', 0);
        $posts_id = [];

            $user_posts = auth()->user()->posts;
            foreach($user_posts as $post){
                $posts_id[]= $post->id;


            }
            foreach($posts_id as $post_id){
                $notification = Notification::where('post_id', $post_id)->get();
                $count += (count($notification));

            }
            session()->put('user_notification', $count);
        return view('frontend.users.dashboard', compact('posts', 'comments'));
    }
    public function create_post(){
        $comments = [];
        $categories = Category::whereStatus(1)->pluck('name', 'id');

        return view('frontend.users.create_post', compact('categories', 'comments'));
    }
    public function store_post(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descreption' => 'required|min:20',
            'status' => 'required',
            'comment_able' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['title'] = $request->title;

        $data['descreption'] = Purify::clean($request->descreption);
        $data['status'] = $request->status;
        $data['comment_able'] = $request->comment_able;
        //$data['user_id'] = $request->user_id;

        $data['category_id'] = $request->category_id;
        $post = auth()->user()->posts()->create($data);

        if ($request->images && count($request->images) > 0) {
            $i = 1;
            foreach ($request->images as $file) {
                $filename = $post->title.'-'.time().'-'.$i.'.'.$file->getClientOriginalExtension();
                $file_size = $file->getSize();
                $file_type = $file->getMimeType();
                $path = public_path('assets/posts/' . $filename);
                Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100);

                $post->media()->create([
                    'file_name' => $filename,
                    'file_size' => $file_size,
                    'file_type' => $file_type,
                ]);
                $i++;
            }
        }

        if ($request->status == 1) {
            Cache::forget('recent_posts');
        }

        return redirect()->back()->with([
            'message' => 'Post created successfully',
            'alert-type' => 'success',
        ]);

    }

    public function edit_post($post_id){
        $comments = [];
        $post = Post::where('id', $post_id)->whereUserId(auth()->id())->first();
        if($post){
            $categories = Category::whereStatus(1)->pluck('name', 'id');
            return view('frontend.users.edit_post', compact('post', 'categories', 'comments'));
        }
        return redirect('user');

    }
    public function update_post(Request $request, $post_id){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descreption' => 'required|min:20',
            'status' => 'required',
            'comment_able' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $post = Post::where('id', $post_id)->whereUserId(auth()->id())->first();
        if($post){
            $data['title'] = $request->title;

            $data['descreption'] = Purify::clean($request->descreption);
            $data['status'] = $request->status;
            $data['comment_able'] = $request->comment_able;
            $data['category_id'] = $request->category_id;
            $post->update($data);
            if ($request->images && count($request->images) > 0) {
                $i = 1;
                foreach ($request->images as $file) {
                    $filename = $post->title.'-'.time().'-'.$i.'.'.$file->getClientOriginalExtension();
                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $path = public_path('assets/posts/' . $filename);
                    Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path, 100);

                    $post->media()->create([
                        'file_name' => $filename,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                    ]);
                    $i++;
                }
            }
            return redirect()->back()->with([
                'message' => 'Post Updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect('user')->back()->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function post_media_destroy($media_id){
        dd('destroy');
        $media = PostMedia::where('id', $media_id)->first();
        if($media){
            if(File::exists('assets/posts'.$media->file_name)){
                unlink('assets/posts'.$media->file_name);
                $media->delete();
                return true;
            }
            return false;
        }
    }
    public function delete_post(Request $request, $post_id){

        $post = Post::where('id', $post_id)->whereUserId(auth()->id())->first();
        if ($post) {
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    if (File::exists('assets/posts/' . $media->file_name)) {
                        unlink('assets/posts/' . $media->file_name);
                    }
                }
            }
            $post->delete();

            return redirect()->back()->with([
                'message' => 'Post deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

    public function show_comments(){
        $posts_id = auth()->user()->posts->pluck('id')->toArray();
        $comments = Comment::where('post_id', $posts_id)->paginate(10);

        return view('frontend.users.comments', compact('comments'));
    }
    public function edit_comment($comment_id){
        $comment = Comment::where('id', $comment_id)->whereHas('post', function($query){
            $query->where('posts.user_id', auth()->id());
        })->first();
        if($comment){
            return view('frontend.users.edit_comment', compact('comment'));
        }else{
            return redirect('user')->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }
    public function update_comment(Request $request, $comment_id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'url' => 'nullable|url',
            'status' => 'required',
            'comment' => 'required',
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $comment = Comment::where('id', $comment_id)->whereHas('post', function($query){
            $query->where('posts.user_id', auth()->id());
        })->first();
            if($comment){
                $data['name'] = $request-> name;
                $data['email'] = $request-> email;
                $data['url'] = $request-> url != '' ? $request->url : null;
                $data['status'] = $request-> status;
                $data['comment'] = Purify::clean($request-> comment);
                $comment->update($data);
                if ($request->status == 1) {
                    Cache::forget('recent_comments');
                }
                return redirect()->back()->with([
                    'message' => 'Commnet updated successfully',
                    'alert-type' => 'success',
                ]);
            }else{
                return redirect()->back()->with([
                    'message' => 'Something was wrong',
                    'alert-type' => 'danger',
                ]);
            }

    }
    public function delete_comment($comment_id){
        $comment = Comment::where('id', $comment_id)->whereHas('post', function($query){
            $query->where('posts.user_id', auth()->id());
        })->first();
            if($comment){
               $comment->delete();
               Cache::forget('recent_comments');
                return redirect()->back()->with([
                    'message' => 'Commnet deleted successfully',
                    'alert-type' => 'success',
                ]);
            }else{
                return redirect('user')->back()->with([
                    'message' => 'Something was wrong',
                    'alert-type' => 'danger',
                ]);
            }
    }
    public function edit_info(){
        $comments = [];
        return view('frontend.users.edit_info', compact('comments'));
    }
    public function update_info(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|numeric',
            'bio' => 'nullable|min:10',
            'recieve_email' => 'required',
            'user_image' => 'nullable'
        ]);

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data['name'] = $request-> name;
        $data['email'] = $request-> email;
        $data['mobile'] = $request-> mobile;
        $data['bio'] = $request-> bio;
        $data['recieve_email'] = $request-> recieve_email;
        if($image = $request->file('user_image')){
            if(auth()->user()->user_image != ''){
                if(File::exists('/assets/user/'.auth()->user()->user_image)){
                    unlink('/assets/user/'.auth()->user()->user_image);
                }
            }
            $filename = auth()->user()->name.'-'.time().'-'.'.'.$image->getClientOriginalExtension();

                $path = public_path('assets/users/' . $filename);
                Image::make($image->getRealPath())->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path, 100); // 100 -> is quality
                $data['user_image'] = $filename;
        }
        $update = auth()->user()->update($data);
        if($update){
            return redirect()->back()->with([
                'message' => 'Information updated successfully',
                'alert-type' => 'success',
            ]);
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }
    public function update_password(Request $request){
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = auth()->user();
        if(Hash::check($request->current_password, $user->password)){
            $update = $user->update([
                'password' => bcrypt($request->password),
            ]);

            if($update){
                return redirect()->back()->with([
                    'message' => 'Password updated successfully',
                    'alert-type' => 'success',
                ]);
            }else{
                return redirect()->back()->with([
                    'message' => 'Something was wrong',
                    'alert-type' => 'danger',
                ]);
            }
        }else{
            return redirect()->back()->with([
                'message' => 'Something was wrong',
                'alert-type' => 'danger',
            ]);
        }
    }
}
