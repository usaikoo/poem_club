<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function favorites() {
        return $this->hasMany(Favorite::class);
    }
}
