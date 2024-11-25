<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id', 'user_id', 'reaction_type'
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
