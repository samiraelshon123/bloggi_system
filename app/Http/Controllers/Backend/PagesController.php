<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Page;
use App\Models\Category;
use App\Models\User;
use App\Models\PostMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Cache;
class PagesController extends Controller
{
    public function pages_index(Request $request)
    {
        $comments = [];
        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
        $categoryId = (isset(\request()->category_id) && \request()->category_id != '') ? \request()->category_id : null;
        $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        $pages = Post::wherePostType('page');
        if($keyword != null){
            $pages = $pages->where('title' , 'LIKE' , "%{$request->keyword}%")->orwhere('descreption' , 'LIKE' , "%{$request->keyword}%");

        }
        if($categoryId != null){
            $pages = $pages->where('category_id' , 'LIKE' , "%{$request->category_id}%");

        }
        if($status != null){

            $pages = $pages->where('status' , 'LIKE' , "%{$request->status}%");
        }

        $pages = $pages->orderBy($sort_by, $order_by);
        $pages = $pages->paginate($limit_by);
        $categories = Category::orderBy('id', 'desc')->pluck('name', 'id');

        
        return view('backend.pages.index', compact('pages', 'categories', 'comments'));
    }
    public function pages_create()
    {
        $comments = [];
        $categories = Category::orderBy('id', 'desc')->pluck( 'name', 'id');

        return view('backend.pages.create', compact('categories', 'comments'));
    }
    public function pages_store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descreption' => 'required|min:20',
            'status' => 'required',
            'category_id' => 'required',
            'images.*' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        if($validator->fails()) {
            
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $page = User::where('name', auth()->user()->name )->get();

        $data['title'] = $request->title;

        $data['descreption'] = Purify::clean($request->descreption);

        $data['status'] = $request->status;

        $data['post_type'] = 'page';

        $data['comment_able'] = 0;

        $data['user_id'] = $page[0]['id'];

        $data['category_id'] = $request->category_id;
        
       $page = Post::create($data);
       
       if ($request->images && count($request->images) > 0) {
        $i = 1;
        foreach ($request->images as $file) {
            $filename = $page->slug.'-'.time().'-'.$i.'.'.$file->getClientOriginalExtension();
            $file_size = $file->getSize();
            $file_type = $file->getMimeType();
            $path = public_path('assets/posts/' . $filename);
            Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            $page->media()->create([
                'file_name' => $filename,
                'file_size' => $file_size,
                'file_type' => $file_type,
            ]);
            $i++;
        }
    }

        return redirect('admin/pages_index')->with([
            'message' => 'Page created successfully',
            'alert-type' => 'success',
        ]);

    }
    public function page_show($page_id)
    {
        $page = Page::with(['media'])->where('id', $page_id)->wherePostType('page')->first();
        return view('backend.pages.show', compact('page'));
    }
    public function pages_edit($id){
        $comments = [];
        $categories = Category::orderBy('id', 'desc')->pluck( 'name', 'id');
        $page = Page::with(['media'])->where('id', $id)->wherePostType('page')->first();
        return view('backend.pages.edit', compact('categories', 'page', 'comments'));
    }
    public function pages_update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descreption' => 'required|min:20',
            'status' => 'required',
            'category_id' => 'required',
             'images.*' => 'nullable|mimes:jpg,jpeg,png,gif|max:20000'
        ]);

        if($validator->fails()) {
            dd('fails');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $page = Page::with(['media'])->where('id', $id)->wherePostType('page')->first();
        if($page){
            $data['title'] = $request->title;

            $data['descreption'] = Purify::clean($request->descreption);
            $data['status'] = $request->status;
            $data['category_id'] = $request->category_id;
            $page->update($data);
            if ($request->images && count($request->images) > 0) {
                $i = 1;
                foreach ($request->images as $file) {
                    $filename = $page->title.'-'.time().'-'.$i.'.'.$file->getClientOriginalExtension();
                    $file_size = $file->getSize();
                    $file_type = $file->getMimeType();
                    $path = public_path('assets/posts/' . $filename);
                    Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($path, 100);

                    $page->media()->create([
                        'file_name' => $filename,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                    ]);
                    $i++;
                }
            }
            return redirect('admin/pages_index')->with([
                'message' => 'Page Updated successfully',
                'alert-type' => 'success',
            ]);

        }
        return redirect('admin/pages_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
    public function pages_destroy($id)
    {

        $page = Post::where('id', $id)->wherePostType('page')->first();
        if ($page) {
            if ($page->media->count() > 0) {
                foreach ($page->media as $media) {
                    if (File::exists('assets/posts/' . $media->file_name)) {
                        unlink('assets/posts/' . $media->file_name);
                    }
                }
            }
            $page->delete();

            return redirect('admin/pages_index')->with([
                'message' => 'Page deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect('admin/pages_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
    public function page_media_destroy($media_id){

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
}
