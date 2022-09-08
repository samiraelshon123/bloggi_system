<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\auth\RegisterController;
use App\Http\Controllers\Frontend\auth\LoginController;
use App\Http\Controllers\Frontend\auth\LogoutController;
use App\Http\Controllers\Backend\auth\AdminLoginController;
use App\Http\Controllers\Frontend\UserController;
use App\Http\Controllers\Frontend\IndexController;
use App\Http\Controllers\Frontend\NotificationsController;

use App\Http\Controllers\Backend\PostsController;
use App\Http\Controllers\Backend\PostCategoriesController;
use App\Http\Controllers\Backend\PostCommentsController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\ContactUsController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\NotificationController;
// Authentication Routes...
Route::get('/register', [RegisterController::class, 'register']);
Route::post('/registerUser', [RegisterController::class, 'registerUser']);


Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/loginUser', [LoginController::class, 'loginUser']);


Route::get('/logout', [LogoutController::class, 'logout']);
Route::get('user', [IndexController::class, 'index']);

Route::group(['prefix' => 'admin'], function() {
    Route::get('/login', [AdminLoginController::class, 'login']);
    Route::post('/loginAdmin', [AdminLoginController::class, 'loginAdmin']);
    Route::post('/logout', [AdminLoginController::class, 'logout']);
});

Route::group(['prefix' => 'admin','middleware' => ['auth:admin']], function() {

    Route::get('/dashboard', [AdminLoginController::class, 'index']);

    //posts

    Route::get('/posts_index', [PostsController::class, 'posts_index']);
    Route::get('/posts_create', [PostsController::class, 'posts_create']);
    Route::post('/posts_store', [PostsController::class, 'posts_store']);
    Route::get('/post_show/{post_id}',  [PostsController::class, 'post_show']);
    Route::get('/posts_edit/{id}', [PostsController::class, 'posts_edit']);
    Route::post('/posts_update/{id}', [PostsController::class, 'posts_update']);
    Route::delete('/posts_destroy/{id}', [PostsController::class, 'posts_destroy']);
    Route::post('/post_media_destroy/{media_id}', [PostsController::class, 'post_media_destroy']);

    Route::get('/category_posts_index/{category_id}', [PostsController::class, 'category_posts_index']);

    //categories

    Route::get('/post_categories_index', [PostCategoriesController::class, 'post_categories_index']);
    Route::get('/post_categories_create', [PostCategoriesController::class, 'post_categories_create']);
    Route::post('/post_categories_store', [PostCategoriesController::class, 'post_categories_store']);
    Route::get('/post_categories_edit/{id}', [PostCategoriesController::class, 'post_categories_edit']);
    Route::post('/post_categories_update/{id}', [PostCategoriesController::class, 'post_categories_update']);
    Route::delete('/post_categories_destroy/{id}', [PostCategoriesController::class, 'post_categories_destroy']);
    
    //comments

    Route::get('/post_comments_index', [PostCommentsController::class, 'post_comments_index']);
    Route::get('/post_comments_edit/{id}', [PostCommentsController::class, 'post_comments_edit']);
    Route::post('/post_comments_update/{id}', [PostCommentsController::class, 'post_comments_update']);
    Route::delete('/post_comments_destroy/{id}', [PostCommentsController::class, 'post_comments_destroy']);
    Route::post('/post_comments_media_destroy/{media_id}', [PostCommentsController::class, 'post_comments_media_destroy']);
    
    //pages

    Route::get('/pages_index', [PagesController::class, 'pages_index']);
    Route::get('/pages_create', [PagesController::class, 'pages_create']);
    Route::post('/pages_store', [PagesController::class, 'pages_store']);
    Route::get('/page_show/{post_id}',  [PagesController::class, 'page_show']);
    Route::get('/pages_edit/{id}', [PagesController::class, 'pages_edit']);
    Route::post('/pages_update/{id}', [PagesController::class, 'pages_update']);
    Route::delete('/pages_destroy/{id}', [PagesController::class, 'pages_destroy']);
    Route::post('/page_media_destroy/{media_id}', [PagesController::class, 'page_media_destroy']);

    //contact us

    Route::get('/contact_us_index', [ContactUsController::class, 'contact_us_index']);
    Route::get('/contact_us_show/{post_id}',  [ContactUsController::class, 'contact_us_show']);
    Route::delete('/contact_us_destroy/{id}', [ContactUsController::class, 'contact_us_destroy']);
   

     //users

     Route::get('/users_index', [UsersController::class, 'users_index']);
     Route::get('/users_create', [UsersController::class, 'users_create']);
     Route::post('/users_store', [UsersController::class, 'users_store']);
     Route::get('/users_show/{post_id}',  [UsersController::class, 'users_show']);
     Route::get('/users_edit/{id}', [UsersController::class, 'users_edit']);
     Route::post('/users_update/{id}', [UsersController::class, 'users_update']);
     Route::delete('/users_destroy/{id}', [UsersController::class, 'users_destroy']);
     Route::post('/user_media_destroy', [UsersController::class, 'user_media_destroy']);
 


    Route::get('/notification/{id}', [NotificationController::class, 'notfication']);
    Route::post('/notifications_update/{id}/{notification_id}', [NotificationController::class, 'notfication_update']);

    Route::get('/supervisors', [SupervisorsController::class, 'supervisors']);
    Route::get('/settings', [SettingsController::class, 'settings']);

   

    // Route::group(['middleware' => ['roles', 'role:admin|editor']], function(){

    // });
});

Route::group(['prefix' => 'user','middleware' => ['auth']], function() {

    Route::get('/dashboard', [UserController::class, 'index']);
    Route::get('/edit_info', [UserController::class, 'edit_info']);
    Route::post('/update_info', [UserController::class, 'update_info']);
    Route::post('/update_password', [UserController::class, 'update_password']);

    Route::get('/create_post', [UserController::class, 'create_post']);
    Route::post('/store_post', [UserController::class, 'store_post']);
    Route::get('/edit_post/{post_id}', [UserController::class, 'edit_post']);
    Route::put('/update_post/{post_id}', [UserController::class, 'update_post']);
    Route::delete('/delete_post/{post_id}', [UserController::class, 'delete_post']);
    Route::post('/post_media_destroy/{media_id}', [UserController::class, 'post_media_destroy']);
    Route::get('/comments', [UserController::class, 'show_comments']);
    Route::get('/edit_comment/{comment_id}', [UserController::class, 'edit_comment']);
    Route::put('/update_comment/{comment_id}', [UserController::class, 'update_comment']);
    Route::delete('/delete_comment/{comment_id}', [UserController::class, 'delete_comment']);
    Route::get('/notification/{notification_id}', [NotificationsController::class, 'notification']);


    Route::get('/post_show/{post_id}', [IndexController::class, 'post_show']);
    Route::post('/store_comment/{post}', [IndexController::class, 'store_comment']);
    Route::get('/category/{category_id}', [IndexController::class, 'category']);
    Route::get('/archive/{data}', [IndexController::class, 'archive']);
    Route::get('/author/{username}', [IndexController::class, 'author']);
    Route::get('/search', [IndexController::class, 'search']);
    Route::get('/contact-us', [IndexController::class, 'contact']);
    Route::post('/contact-us', [IndexController::class, 'do_contact']);

});

