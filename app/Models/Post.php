<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Post extends Model
{

    
    protected $guarded = [];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approved_comments()
    {
        return $this->hasMany(Comment::class)->whereStatus(1);
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }

    public function status()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }
    public function notification(){
        return $this->hasMany(Notification::class);
    }
    public function admin_notification(){
        return $this->hasMany(Notification::class);
    }

}
