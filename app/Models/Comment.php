<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['name', 'email', 'url', 'ip_address', 'status', 'comment', 'post_id', 'user_id'];
    protected $guard = [];
    public function post(){
        return $this->belongsTo(Post::class);
    }
    public function status(){
        return $this->status == 1 ? 'Active' : 'Inactive'; 
    }
}
