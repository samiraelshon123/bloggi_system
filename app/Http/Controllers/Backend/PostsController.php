<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Models\PostMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Cache;
class PostsController extends Controller
{
    
    public function posts_index(Request $request)
    {
        $comments = [];
        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
        $categoryId = (isset(\request()->category_id) && \request()->category_id != '') ? \request()->category_id : null;
        $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        $posts = Post::with(['media', 'category', 'comments'])->wherePostType('post');
        if($keyword != null){
            $posts = $posts->where('title' , 'LIKE' , "%{$request->keyword}%")->orwhere('descreption' , 'LIKE' , "%{$request->keyword}%");

        }
        if($categoryId != null){
            $posts = $posts->where('category_id' , 'LIKE' , "%{$request->category_id}%");

        }
        if($status != null){

            $posts = $posts->where('status' , 'LIKE' , "%{$request->status}%");
        }

        $posts = $posts->orderBy($sort_by, $order_by);
        $posts = $posts->paginate($limit_by);
        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');


        return view('backend.posts.index', compact('posts', 'categories', 'comments'));
    }
    public function posts_create()
    {
        $comments = [];
        $categories = Category::orderBy('id', 'desc')->pluck( 'name', 'id');

        return view('backend.posts.create', compact('categories', 'comments'));
    }
    public function posts_store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descreption' => 'required|min:20',
            'status' => 'required',
            'comment_able' => 'required',
            'category_id' => 'required',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $post = User::where('name', auth()->user()->name )->get();

        $data['title'] = $request->title;

        $data['descreption'] = Purify::clean($request->descreption);

        $data['status'] = $request->status;

        $data['post_type'] = 'post';

        $data['comment_able'] = $request->comment_able;

        $data['user_id'] = $post[0]['id'];

        $data['category_id'] = $request->category_id;

       $post = Post::create($data);
        //$post = auth()->user()->posts()->create($data);

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

        return redirect('admin/posts_index')->with([
            'message' => 'Post created successfully',
            'alert-type' => 'success',
        ]);

    }
    public function post_show($post_id)
    {
        $post = Post::with(['media', 'category', 'user', 'comments'])->where('id', $post_id)->wherePostType('post')->first();
        return view('backend.posts.show', compact('post'));
    }
    public function posts_edit($id){
        $comments = [];
        $categories = Category::orderBy('id', 'desc')->pluck( 'name', 'id');
        $post = Post::with(['media'])->where('id', $id)->wherePostType('post')->first();
        return view('backend.posts.edit', compact('categories', 'post', 'comments'));
    }
    public function posts_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descreption' => 'required|min:20',
            'status' => 'required',
            'comment_able' => 'required',
            'category_id' => 'required',
             'images.*' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $post = Post::with(['media'])->where('id', $id)->wherePostType('post')->first();
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
            return redirect('admin/posts_index')->with([
                'message' => 'Post Updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect('admin/posts_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
    public function posts_destroy($id)
    {

        $post = Post::where('id', $id)->wherePostType('post')->first();
        if ($post) {
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    if (File::exists('assets/posts/' . $media->file_name)) {
                        unlink('assets/posts/' . $media->file_name);
                    }
                }
            }
            $post->delete();

            return redirect('admin/posts_index')->with([
                'message' => 'Post deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect('admin/posts_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
    public function post_media_destroy($media_id){

        dd('destroy media');

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
    
    public function category_posts_index($category_id){
        $posts = Post::with(['media', 'category', 'comments'])->where('category_id', $category_id)->wherePostType('post')->orderBy('id', 'desc')->paginate(10);
        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');
        return view('backend.posts.index', compact('posts', 'categories'));
    }
}
