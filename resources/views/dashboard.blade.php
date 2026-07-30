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
            @forelse($posts as $post)
            <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                <!-- Header Post -->
                <div class="p-4 border-b flex items-center justify-between">
                    <span class="font-bold text-gray-800">{{ $post->user->name }}</span>
                    <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                </div>

                <!-- Gambar Post -->
                <div>
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full h-auto object-cover max-h-[500px]">
                </div>

                <!-- Caption -->
                <div class="p-4">
                    @if($post->caption)
                    <p class="text-gray-800"><span class="font-bold mr-2">{{ $post->user->name }}</span>{{ $post->caption }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                Belum ada postingan. Jadilah yang pertama posting!
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>