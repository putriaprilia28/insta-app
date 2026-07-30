<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:500',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Komentar terkirim!');
    }

    public function destroy(Comment $comment)
    {
        // Hak akses: hanya pembuat komentar atau pembuat post yang bisa hapus
        if (auth()->id() !== $comment->user_id && auth()->id() !== $comment->post->user_id) {
            abort(403, 'Tidak memiliki akses untuk menghapus komentar ini.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}
