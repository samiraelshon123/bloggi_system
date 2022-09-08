<?php

namespace App\Http\Controllers\Frontend;
use App\Models\Post;
use App\Models\User;
use App\Models\Notification;
use App\Models\AdminNotification;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Nullable;
use Stevebauman\Purify\Facades\Purify;
use App\Notifications\NewCommentForAdminNotify;
use App\Notifications\NewCommentForPostOwnerNotify;

class IndexController extends Controller
{
    public function index(){
        $count = 0;
        $comments = [];
        session()->put('user_notification', 0);
        $posts_id = [];

        if(auth()->user()){
            $comments = Notification::all();
            $posts = auth()->user()->posts;
            foreach($posts as $post){
                $posts_id[]= $post->id;


            }
            foreach($posts_id as $post_id){
                $notification = Notification::where('post_id', $post_id)->get();
                $count += (count($notification));

            }
            session()->put('user_notification', $count);
            
        }

//category && media && user -> relationship between table
        $posts = Post::with([ 'media', 'user'])
        ->whereHas('category', function($query){
            $query->whereStatus(1);
        })
        ->whereHas('user', function($query){
            $query->whereStatus(1);
        })
        ->wherePostType('post')->whereStatus(1)->orderBy('id', 'desc')->paginate(5);

        return view('frontend.index', compact('posts', 'comments'));
    }
    public function search(Request $request){

            $posts = Post::with(['category', 'media', 'user'])->when($request->keyword,function($query)use($request)
            {
                $query->where('title' , 'LIKE' , "%{$request->keyword}%")->orwhere('descreption' , 'LIKE' , "%{$request->keyword}%");
            })->whereHas('category', function($query){
                $query->whereStatus(1);
            })
            ->whereHas('user', function($query){
                $query->whereStatus(1);
            })->wherePostType('post')->whereStatus(1)->orderBy('id', 'desc')->paginate(5);
        $comments = [];

        return view('frontend.index', compact('posts', 'comments'));
    }
    public function post_show($post_id){

        $comments = [];
        $title = '';
        if($post_id == 'About Us' || $post_id == 'Our Vesion'){

            $blade = 'page';

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

            $post = $post->where('title' , $post_id);

            $post = $post->wherePostType('page')->whereStatus(1)->first();



        }else{

            $blade = 'post';
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

        $post = $post->where('id' , $post_id);
        $post = $post->wherePostType('post')->whereStatus(1)->first();

        }

        if($post){

            return view('frontend.'.$blade, compact('post', 'comments'));
        }else
            return redirect('user');
    }

    public function store_comment(Request $request, $id){


        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'url' => 'nullable|url',
            'comment' => 'required|min:10'
        ]);

        if($validation->fails()){

            return redirect()->back()->withErrors($validation)->withInput();
        }

        $post = Post::where('id', $id)->wherePostType('post')->whereStatus(1)->first();

        if($post){
            $userId = auth()->check() ? auth()->id() : null;

            $data['name'] = $request->name;

            $data['email'] = $request->email;
            $data['url'] = $request->url;
            $data['ip_address'] = $request->ip();

            $data['comment'] = Purify::clean($request->comment);

            $data['post_id'] = $id;

            $data['user_id'] = $userId;

          $title = ( Post::where('id', $id)->get()[0]['title']);
            $post->comments()->create($data); // relationship between post & comment
            Notification::create( [

                'post_id' => $id,
                'post_title' => $title,
                'data' => 'name: '.request('name').' email: '.request(),


            ]);
            AdminNotification::create( [


                'post_id' => $id,
                'post_title' => $title,
                'user_id' => $userId,


            ]);
            return redirect()->back()->with([
                'message' => 'Comment Added Successfully',
                'alert-type' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Something Was Wrong',
            'alert-type' => 'danger'
        ]);
    }
    public function contact(){
        $comments = [];
        return view('frontend.contact', compact('comments'));
    }
    public function do_contact(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'nullable|numeric',
            'title' => 'required|min:5',
            'message' => 'required|min:10'
        ]);

        if($validation->fails()){

            return redirect()->back()->withErrors($validation)->withInput();
        }

        $data['name'] = $request-> name;
        $data['email'] = $request-> email;
        $data['mobile'] = $request-> mobile;
        $data['title'] = $request-> title;
        $data['message'] = $request-> message;
        Contact::create($data);
        return redirect()->back()->with([
            'message' => 'Message Sent Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function category($id){
        $comments = [];
        $category = Category::where('id', $id)->whereStatus(1)->first()->id;
        if($category){
            $posts = Post::with(['media', 'user'])
            ->whereCategoryId($id)
            ->wherePostType('post')
            ->whereStatus(1)
            ->orderBy('id', 'desc')
            ->paginate(5);
            return view('frontend.index', compact('posts', 'comments'));
        }
        return redirect('user');
    }
    public function archive($date){
        $comments = [];
        $exploded_date = explode('-', $date);
        $month = $exploded_date[0];
        $year = $exploded_date[1];
        $posts = Post::with(['media', 'user', 'category'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->wherePostType('post')
            ->whereStatus(1)
            ->orderBy('id', 'desc')
            ->paginate(5);
            return view('frontend.index', compact('posts', 'comments'));
    }
    public function author($username){
        $user = User::where('username', $username)->whereStatus(1)->first()->id;
        if($user){
            $posts = Post::with(['media', 'user'])
            ->whereUserId($user)
            ->wherePostType('post')
            ->whereStatus(1)
            ->orderBy('id', 'desc')
            ->paginate(5);
            return view('frontend.index', compact('posts'));
        }
        return redirect('user');
    }
}
