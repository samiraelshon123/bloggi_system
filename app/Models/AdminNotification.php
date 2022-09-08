<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = ['post_id','post_title', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
