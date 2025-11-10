@php use Illuminate\Support\Str; @endphp
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Posts') }}
    </h2>
  </x-slot>
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      @if(session('status'))
        <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
      @endif

      <div class="mb-4">
        <a href="{{ route('posts.create') }}" class="inline-flex items-center rounded border px-3 py-1 bg-blue-500 text-white hover:bg-blue-600">Create New Post</a>
      </div>

      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        @foreach($posts as $post)
          <div class="border-b last:border-0 p-4">
            <h3 class="text-lg font-semibold">{{ $posts->title }}</h3>
            <p class="mt-2 text-gray-600">{{ Str::limit($post->content, 150) }}</p>
            <a href="{{ route('posts.show', $post) }}" class="text-blue-500 hover:underline mt-2 inline-block">Read More</a>
          </div>
        @endforeach
      </div>

      <div class="mt-4">
        {{ $posts->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
