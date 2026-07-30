<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('InstaApp Feed') }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">

        <!-- Pesan Sukses -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        <!-- Form Buat Postingan Baru -->
        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <h3 class="font-bold text-lg mb-4">Buat Postingan Baru</h3>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Gambar</label>
                    <input type="file" name="image" accept="image/*" required class="w-full text-sm text-gray-500 border border-gray-300 rounded-lg p-2">
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <textarea name="caption" rows="2" placeholder="Tulis caption..." class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('caption') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold text-sm">
                    Bagikan
                </button>
            </form>
        </div>

        <!-- Feed Postingan -->
        <div class="space-y-6">
            <!-- Feed Postingan -->
            <div class="space-y-6">
                @forelse($posts as $post)
                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                    <!-- Header Post -->
                    <div class="p-4 border-b flex items-center justify-between">
                        <span class="font-bold text-gray-800">{{ $post->user->name }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>

                            <!-- Tombol Hapus Post (Hanya untuk Pemilik Post) -->
                            @if(auth()->id() === $post->user_id)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus post ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:underline">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <!-- Gambar Post -->
                    <div>
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full h-auto object-cover max-h-[500px]">
                    </div>

                    <!-- Aksi (Like & Jumlah Like) -->
                    <div class="p-4 border-b">
                        <div class="flex items-center space-x-4 mb-2">
                            <form action="{{ route('posts.like', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center space-x-1 font-bold text-sm focus:outline-none">
                                    @if($post->isLikedBy(auth()->user()))
                                    <span class="text-red-500">❤️ Suka</span>
                                    @else
                                    <span class="text-gray-500 hover:text-red-500">🤍 Suka</span>
                                    @endif
                                </button>
                            </form>
                        </div>
                        <span class="text-xs font-semibold text-gray-700">{{ $post->likes->count() }} Menyukai</span>
                    </div>

                    <!-- Caption & Komentar -->
                    <div class="p-4">
                        @if($post->caption)
                        <p class="text-gray-800 mb-4"><span class="font-bold mr-2">{{ $post->user->name }}</span>{{ $post->caption }}</p>
                        @endif

                        <!-- Daftar Komentar -->
                        <div class="space-y-2 mb-4 max-h-40 overflow-y-auto border-t pt-3">
                            @forelse($post->comments as $comment)
                            <div class="text-sm flex justify-between items-start">
                                <div>
                                    <span class="font-bold mr-2">{{ $comment->user->name }}</span>
                                    <span class="text-gray-700">{{ $comment->body }}</span>
                                </div>

                                <!-- Tombol Hapus Komentar -->
                                @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 ml-2">×</button>
                                </form>
                                @endif
                            </div>
                            @empty
                            <p class="text-xs text-gray-400">Belum ada komentar.</p>
                            @endforelse
                        </div>

                        <!-- Form Input Komentar -->
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="flex gap-2 border-t pt-3">
                            @csrf
                            <input type="text" name="body" placeholder="Tambah komentar..." required class="flex-1 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <button type="submit" class="bg-gray-800 text-white px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-gray-900">
                                Kirim
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    Belum ada postingan. Jadilah yang pertama posting!
                </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>