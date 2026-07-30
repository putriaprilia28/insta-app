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
        $posts = Post::with('user')->latest()->get();
        return view('dashboard', compact('posts'));
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
