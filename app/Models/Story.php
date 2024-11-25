<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class Story extends Model
    {
        use HasFactory;
    
        protected $fillable = [
            'user_id', 'image_url', 'video_url', 'expires_at'
        ];
    
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    
        public function views()
        {
            return $this->hasMany(StoryView::class);
        }
    
        public function reactions()
        {
            return $this->hasMany(StoryReaction::class);
        }
    
        public function hasViewed($userId)
        {
            return $this->views()->where('viewer_id', $userId)->exists();
        }
    
        public function getDurationAttribute()
        {
            return now()->diffInSeconds($this->expires_at);
        }
    }
