<?php

namespace App\Notes\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'is_markdown', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
