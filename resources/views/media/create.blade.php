<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Media Library') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h1 class="text-2xl font-bold mb-4">Media Library</h1>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($mediaItems as $media)
              <div class="border rounded-lg overflow-hidden">
                <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->filename }}" class="w-full h-48 object-cover">
                <div class="p-4">
                  <h2 class="text-lg font-semibold">{{ $media->filename }}</h2>
                  <p class="text-sm text-gray-600">Uploaded on: {{ $media->created_at->format('M d, Y') }}</p>
                </div>
              </div>
            @endforeach
          </div>

          <div class="mt-4">
            {{ $mediaItems->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>