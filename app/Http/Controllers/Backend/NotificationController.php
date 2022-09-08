<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function notfication($id){
       
        $notification_comment = [];
        $comments =[];
        $notification = AdminNotification::find($id);
        if($notification){
        $notification_comment = Comment::where('user_id', $notification->user_id)->where('post_id', $notification->post_id)->get();
        }
        
        if(session('notification') > 0){
           session()->decrement('notification');
        }
        
        return view('backend.edit_notification', compact('notification', 'comments', 'notification_comment', 'id'));
    }
    public function notfication_update(Request  $request, $notification_comment, $notification){
        
        $notification = AdminNotification::find($notification);
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'ip_address' => 'required',
            'status' => 'required',
            'comment' => 'required',
            
        ]);
        
       
        $comment = Comment::where('id', $notification_comment)->first();
        
        if($comment){
           
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['ip_address'] = $request->ip_address;
            $data['status'] = $request->status;
            $data['comment'] = $request->comment;
            
            $comment->update($data);
            
            

        }
        if($notification){
            $notification->delete();
        }
        return redirect()->back();
        
       
    }
}
