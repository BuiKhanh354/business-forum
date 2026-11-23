<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'author_name',
        'author_email',
        'content',
        'status',
        'is_read',
        'likes_count',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'likes_count' => 'integer',
    ];

    /**
     * Bài viết mà bình luận này thuộc về
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
