<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Menampilkan halaman dashboard / feed
    public function index()
    {
        // Load relasi user, likes, dan comments
        $posts = Post::with(['user', 'likes', 'comments.user'])->latest()->get();
        return view('dashboard', compact('posts'));
    }

    public function destroy(Post $post)
    {
        // Pengecekan hak akses (Hanya pembuat post yang boleh hapus)
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Tidak memiliki akses untuk menghapus postingan ini.');
        }

        // Hapus gambar dari storage
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Postingan berhasil dihapus!');
    }

    // Menyimpan postingan baru
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:1000',
        ]);

        // Simpan file gambar ke folder storage/app/public/posts
        $imagePath = $request->file('image')->store('posts', 'public');

        // Simpan ke database
        $request->user()->posts()->create([
            'image' => $imagePath,
            'caption' => $request->caption,
        ]);

        return redirect()->back()->with('success', 'Postingan berhasil diunggah!');
    }
}
