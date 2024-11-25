<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\StoryView;
use App\Models\StoryReaction;
use Illuminate\Http\Request;
use Auth;

class StoryController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|mimes:mp4,mkv,avi|max:20480', // Optional video
        ]);

        $user = Auth::user();
        $story = new Story();
        $story->user_id = $user->id;
        $story->expires_at = now()->addMinutes(24 * 60); // 24 hours
        $story->image_url = $request->file('image')->store('stories');
        if ($request->hasFile('video')) {
            $story->video_url = $request->file('video')->store('stories/videos');
        }
        $story->save();

        return response()->json($story, 201);
    }

    public function viewStory($storyId)
    {
        $story = Story::findOrFail($storyId);
        $user = Auth::user();

        if (!$story->hasViewed($user->id)) {
            StoryView::create([
                'story_id' => $story->id,
                'viewer_id' => $user->id
            ]);
        }

        // Fetch reactions and viewers
        $reactions = $story->reactions;
        $views = $story->views;

        return response()->json([
            'story' => $story,
            'reactions' => $reactions,
            'views' => $views
        ]);
    }

    public function reactToStory(Request $request, $storyId)
    {
        $request->validate([
            'reaction_type' => 'required|in:like,love,wow,sad,angry'
        ]);

        $story = Story::findOrFail($storyId);
        $user = Auth::user();

        $reaction = StoryReaction::firstOrCreate([
            'story_id' => $story->id,
            'user_id' => $user->id
        ], [
            'reaction_type' => $request->reaction_type
        ]);

        return response()->json($reaction, 200);
    }
}
