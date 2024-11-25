<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id', 'viewer_id'
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
