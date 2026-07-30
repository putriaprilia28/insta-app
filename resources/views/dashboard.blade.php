<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-xl mx-auto">
            <!-- Header Logo Gaya Vintage -->
            <h2 class="font-bold text-2xl text-[#4e3629] tracking-wide flex items-center gap-2 font-serif">
                📷 <span class="border-b-2 border-[#8c6239] pb-0.5">InstaApp</span> <span class="text-xs bg-[#8c6239] text-[#f4efe6] px-2 py-0.5 rounded font-sans tracking-normal uppercase">Classic</span>
            </h2>
            <span class="text-xs font-semibold text-[#6e5038] bg-[#e8decb] px-3 py-1 rounded-full border border-[#d2c2a5]">
                {{ auth()->user()->name }}
            </span>
        </div>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4 bg-[#f8f5ee] min-h-screen">

        <!-- Alert Sukses -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-[#e2ece9] text-[#2d5a27] border border-[#b8d8be] rounded-lg flex items-center justify-between text-sm shadow-sm">
            <span>✨ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-bold hover:opacity-75">&times;</button>
        </div>
        @endif

        <!-- Card Form Upload - Style Retro Polaroid / Leather Card -->
        <div class="bg-[#efe9dc] rounded-lg border-2 border-[#d6c7b0] shadow-md p-5 mb-8">
            <h3 class="font-bold text-[#4e3629] text-sm uppercase tracking-wider mb-3 flex items-center gap-2 border-b border-[#d6c7b0] pb-2">
                <span>📸</span> Unggah Foto Baru
            </h3>

            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Input File -->
                <div>
                    <label class="block text-xs font-bold text-[#6e5038] uppercase mb-1">Pilih File Foto</label>
                    <input type="file" name="image" accept="image/*" required
                        class="block w-full text-xs text-[#4e3629] file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-[#8c6239] file:text-white hover:file:bg-[#6e5038] border border-[#d6c7b0] rounded bg-white p-1 focus:outline-none transition cursor-pointer">
                    @error('image') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Input Caption -->
                <div>
                    <label class="block text-xs font-bold text-[#6e5038] uppercase mb-1">Keterangan / Caption</label>
                    <textarea name="caption" rows="2" placeholder="Bagikan cerita fotomu..."
                        class="w-full rounded border-[#d6c7b0] focus:border-[#8c6239] focus:ring-[#8c6239] text-sm p-2.5 bg-white text-[#332219] resize-none"></textarea>
                    @error('caption') <span class="text-red-600 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Tombol Submit Warna Cokelat Classic -->
                <button type="submit"
                    class="w-full bg-[#8c6239] hover:bg-[#6e5038] text-[#f8f5ee] font-bold py-2.5 px-4 rounded shadow transition text-xs uppercase tracking-wider flex items-center justify-center gap-2">
                    📤 Bagikan Ke Feed
                </button>
            </form>
        </div>

        <!-- Feed Postingan -->
        <div class="space-y-8">
            @forelse($posts as $post)
            <div class="bg-white rounded-lg border-2 border-[#d6c7b0] shadow-md overflow-hidden">

                <!-- Header Post - Gaya Header Instagram Cokelat -->
                <div class="p-3 bg-[#efe9dc] border-b border-[#d6c7b0] flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Avatar Inisial Cokelat -->
                        <div class="w-8 h-8 rounded bg-[#8c6239] text-[#f8f5ee] flex items-center justify-center font-bold text-xs uppercase shadow-sm">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-xs text-[#4e3629] leading-none">{{ $post->user->name }}</h4>
                            <span class="text-[10px] text-[#8c7a6b]">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Tombol Hapus Post -->
                    @if(auth()->id() === $post->user_id)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Hapus postingan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-700 hover:text-red-900 font-bold px-2 py-1 rounded bg-[#e6d8c3] hover:bg-red-100 transition" title="Hapus Post">
                            Hapus
                        </button>
                    </form>
                    @endif
                </div>

                <!-- Gambar Post dengan Bingkai Tebal Tipis Ala Foto Cetak -->
                <div class="p-2 bg-[#fdfbf7]">
                    <div class="border border-[#e2d7c5] overflow-hidden bg-black/5 flex items-center justify-center max-h-[500px]">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full h-auto object-cover">
                    </div>
                </div>

                <!-- Tombol Like (Suka) & Jumlah -->
                <div class="px-4 py-2 bg-[#f9f6f0] border-t border-b border-[#ebd2c5]">
                    <div class="flex items-center justify-between">
                        <form action="{{ route('posts.like', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 focus:outline-none font-bold text-xs">
                                @if($post->isLikedBy(auth()->user()))
                                <span class="text-red-600">❤️ Menyukai</span>
                                @else
                                <span class="text-[#8c7a6b] hover:text-red-600">🤍 Suka</span>
                                @endif
                            </button>
                        </form>
                        <span class="text-xs font-bold text-[#4e3629]">{{ $post->likes->count() }} Menyukai</span>
                    </div>
                </div>

                <!-- Caption & Komentar -->
                <div class="p-4 bg-[#fdfbf7] space-y-3">
                    @if($post->caption)
                    <p class="text-xs text-[#332219] leading-relaxed border-b border-[#f0e6d6] pb-2">
                        <span class="font-bold mr-1.5 text-[#4e3629]">{{ $post->user->name }}:</span>{{ $post->caption }}
                    </p>
                    @endif

                    <!-- Daftar Komentar -->
                    <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                        @forelse($post->comments as $comment)
                        <div class="text-xs flex justify-between items-start bg-[#f4efe6] p-2 rounded border border-[#e8decb]">
                            <div class="leading-relaxed">
                                <span class="font-bold text-[#4e3629] mr-1">{{ $comment->user->name }}:</span>
                                <span class="text-[#554135]">{{ $comment->body }}</span>
                            </div>

                            @if(auth()->id() === $comment->user_id || auth()->id() === $post->user_id)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold ml-2">
                                    &times;
                                </button>
                            </form>
                            @endif
                        </div>
                        @empty
                        <p class="text-[11px] text-[#a09082] italic">Belum ada komentar.</p>
                        @endforelse
                    </div>

                    <!-- Form Input Komentar -->
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="flex items-center gap-2 pt-2 border-t border-[#f0e6d6]">
                        @csrf
                        <input type="text" name="body" placeholder="Tulis komentar..." required
                            class="flex-1 text-xs border-[#d6c7b0] rounded focus:ring-[#8c6239] focus:border-[#8c6239] p-2 bg-white text-[#332219]">
                        <button type="submit" class="bg-[#5c4033] hover:bg-[#3d2a21] text-white text-xs font-bold px-3 py-2 rounded transition">
                            Kirim
                        </button>
                    </form>
                </div>

            </div>
            @empty
            <div class="bg-[#efe9dc] rounded-lg p-10 text-center border-2 border-[#d6c7b0]">
                <p class="text-3xl mb-2">🎞️</p>
                <p class="text-[#4e3629] font-bold text-sm">Belum Ada Postingan</p>
                <p class="text-[#8c7a6b] text-xs mt-1">Unggah foto pertamamu di atas!</p>
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>