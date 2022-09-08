<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactUsController extends Controller
{
    
    public function contact_us_index(Request $request)
    {
        $comments = [];
        
        $keyword = (isset(\request()->keyword) && \request()->keyword != '') ? \request()->keyword : null;
       $status = (isset(\request()->status) && \request()->status != '') ? \request()->status : null;
        $sort_by = (isset(\request()->sort_by) && \request()->sort_by != '') ? \request()->sort_by : 'id';
        $order_by = (isset(\request()->order_by) && \request()->order_by != '') ? \request()->order_by : 'desc';
        $limit_by = (isset(\request()->limit_by) && \request()->limit_by != '') ? \request()->limit_by : '10';


        $messages = Contact::query();
        if($keyword != null){
            $messages = $messages->where('name' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('email' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('mobile' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('title' , 'LIKE' , "%{$request->keyword}%")->
            orwhere('message' , 'LIKE' , "%{$request->keyword}%");

        }
     
        if($status != null){

            $messages = $messages->where('status' , 'LIKE' , "%{$request->status}%");
        }

        $messages = $messages->orderBy($sort_by, $order_by);
        $messages = $messages->paginate($limit_by);


        return view('backend.contact_us.index', compact('messages', 'comments'));
    }

    public function contact_us_show($id)
    {
        $comments = [];
        $message = Contact::where('id', $id)->first();
        if($message && $message->status == 0){
            $message->status = 1;
            $message->save();
        }
        return view('backend.contact_us.show', compact('message', 'comments'));
    }

    public function contact_us_destroy($id)
    {

        $message = Contact::where('id', $id)->first();
        if ($message) {
            
            $message->delete();

            return redirect('admin/contact_us_index')->with([
                'message' => 'Message deleted successfully',
                'alert-type' => 'success',
            ]);
        }

        return redirect('admin/contact_us_index')->with([
            'message' => 'Something was wrong',
            'alert-type' => 'danger',
        ]);
    }
}
