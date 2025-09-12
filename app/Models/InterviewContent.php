<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'question1',
        'question2',
        'question3',
        'question4',
        'question5',
    ];

    /**
     * 投稿とのリレーション
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
