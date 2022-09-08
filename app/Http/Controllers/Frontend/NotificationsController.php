<?php

namespace App\Http\Controllers\Frontend;
use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PostMedia;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function notification($notification_id){

        $notification = Notification::find($notification_id);

        $comments = [];
        if($notification){
            $post = Post::with(['category', 'media', 'user',
            'approved_comments' => function($query){
               $query->orderBy('id', 'desc');
           }
           ]);

            $post = $post
           ->whereHas('category', function($query){
               $query->whereStatus(1);
           })
           ->whereHas('user', function($query){
               $query->whereStatus(1);
           });

           $post = $post->where('id' , $notification['post_id']);

           $post = $post->wherePostType('post')->whereStatus(1)->first();


           $notification->delete();
           if(session('user_notification') > 0){
            session()->decrement('user_notification');
           }

           if($post){

            return view('frontend.post', compact('post', 'comments'));
        }else
            return redirect('user');
        }





    }
}
