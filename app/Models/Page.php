<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $guarded = [];
    
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function media(){
        return $this->hasMany(PostMedia::class, 'post_id', 'id');
    }
    public function status()
    {
        return $this->status == 1 ? 'Active' : 'Inactive';
    }
}
