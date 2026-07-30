<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $userId = auth()->id();

        $like = Like::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete(); // Untoggle (Unlike)
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
        }

        return redirect()->back();
    }
}
