<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name', 'email', 'mobile', 'title', 'message'];
    protected $guard = [];
    public function status()
    {
        return $this->status == 1 ? 'Read' : 'New';
    }
}
