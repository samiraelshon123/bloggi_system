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
class PostCategoriesController extends Controller
{
    
    public function post_categories_index(Request $request)
    {
        $comments = [];
        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
        $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        $categories = Category::withCount('posts');
        if($keyword != null){
            $categories = $categories->where('name' , 'LIKE' , "%{$request->keyword}%");
            
        }
        
        if($status != null){

            $categories = $categories->where('status' , 'LIKE' , "%{$request->status}%");



        }

        $categories = $categories->orderBy($sort_by, $order_by);
        $categories = $categories->paginate($limit_by);

        return view('backend.posts_categories.index', compact('categories', 'comments'));
    }
    public function post_categories_create()
    {
       $comments = [];
        return view('backend.posts_categories.create', compact('comments'));
    }
    public function post_categories_store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $post = User::where('name', auth()->user()->name )->get();

        $data['name'] = $request->name;
        $data['status'] = $request->status;
        Post::create($data);
        
        if ($request->status == 1) {
            Cache::forget('global_categories');
        }

        return redirect('admin/post_categories_index')->with([
            'message' => 'Category created successfully',
            'alert-type' => 'success',
        ]);

    }
    public function post_categories_edit($id){
        $comments = [];
        $category = Category::where('id', $id)->first();
        return view('backend.posts_categories.edit', compact('category', 'comments'));
    }
    public function post_categories_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'status' => 'required',
            
        ]);

        if($validator->fails()) {
           
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $category = Category::where('id', $id)->first();
        if($category){
            $data['name'] = $request->name;
            $data['status'] = $request->status;
            $category->update($data);
            
            Cache::forget('global_categories');
            
            return redirect('admin/post_categories_index')->with([
                'message' => 'Category Updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect('admin/post_categories_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
    public function post_categories_destroy($id)
    {
        $category = Category::where('id', $id)->first();
        foreach($category->posts as $post){
            if ($post->media->count() > 0) {
                foreach ($post->media as $media) {
                    if (File::exists('assets/posts/' . $media->file_name)) {
                        unlink('assets/posts/' . $media->file_name);
                    }
                }
            }
        }
        $category->delete();
        return redirect('admin/post_categories_index')->with([
                'message' => 'Category deleted successfully',
                'alert-type' => 'success',
            ]);
        
    }
    
}
