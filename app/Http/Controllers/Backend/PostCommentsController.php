<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use App\Models\PostMedia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Purify\Facades\Purify;
use Illuminate\Support\Facades\Cache;

class PostCommentsController extends Controller
{
    public function post_comments_index(Request $request)
    {

        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
        $postId = (isset(\request()->post_id) && \request()->post_id != '') ? \request()->post_id : null;
        $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        $comments = Comment::query();
        if($keyword != null){
            $comments = $comments->where('name' , 'LIKE' , "%{$request->keyword}%")->orwhere('comment' , 'LIKE' , "%{$request->keyword}%");

        }
        if($postId != null){
            $comments = $comments->where('post_id' , 'LIKE' , "%{$request->post_id}%");

        }
        if($status != null){

            $comments = $comments->where('status' , 'LIKE' , "%{$request->status}%");
        }

        $comments = $comments->orderBy($sort_by, $order_by);
        $comments = $comments->paginate($limit_by);


        $posts = Post::wherePostType('post')->pluck('title', 'id');


        return view('backend.post_comments.index', compact('posts', 'comments'));
    }

    public function post_comments_edit($id){

       $comments = [];
        $comment = Comment::where('post_id', $id)->first();

        return view('backend.post_comments.edit', compact('comment', 'comments'));
    }
    public function post_comments_update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'url' => 'nullable|url',
            'status' => 'required',
            'comment' => 'required',

        ]);

        if($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $comment = Comment::where('id', $id)->first();
        if ($comment) {
            $data['name']           = $request->name;
            $data['email']          = $request->email;
            $data['url']            = $request->url;
            $data['status']         = $request->status;
            $data['comment']        = Purify::clean($request->comment);

            $comment->update($data);

            Cache::forget('recent_comments');

            return redirect('admin/post_comments_index')->with([
                'message' => 'Comment updated successfully',
                'alert-type' => 'success',
            ]);

        }

        return redirect('admin/post_comments_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
    public function post_comments_destroy($id)
    {

        $comment = Comment::where('id', $id)->first();
            $comment->delete();

            return redirect('admin/post_comments_index')->with([
                'message' => 'Comment deleted successfully',
                'alert-type' => 'success',
            ]);


        return redirect('admin/post_comments_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }

}
