<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Flux</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <form action="{{ route('posts.store') }}" method="post" class="mb-6 bg-white p-4 rounded border">
                @csrf
                <textarea name="body" rows="3" class="w-full border rounded p-2" placeholder="Quoi de neuf ?">{{ old('body') }}</textarea>
                @error('body') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror

                <div class="mt-3">
                    <label class="block text-sm text-gray-700">Média (optionnel)</label>
                    <select name="media_id" class="border rounded p-2 w-full max-w-md">
                        <option value="">—</option>
                        @foreach($myMedia as $m)
                            <option value="{{ $m->id }}" @selected(old('media_id')==$m->id)>{{ $m->original_name }}</option>
                        @endforeach
                    </select>
                    @error('media_id') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>

                <button class="mt-3 inline-flex items-center rounded border px-3 py-1">Publier</button>
            </form>

            @foreach($posts as $p)
                <article class="border p-4 mb-4 bg-white rounded">
                    <div class="text-sm text-gray-600">
                        par {{ $p->user->username ?? $p->user->name }} · {{ $p->created_at->diffForHumans() }}
                    </div>
                    <p class="mt-2 whitespace-pre-line">{{ $p->body }}</p>
                    @if($p->media)
                        <img src="{{ Storage::url($p->media->path) }}" class="mt-3 max-h-64 object-cover rounded" />
                    @endif
                    @can('delete',$p)
                        <form action="{{ route('posts.destroy',$p) }}" method="post" class="mt-2">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm">Supprimer</button>
                        </form>
                    @endcan
                </article>
            @endforeach

            <div class="mt-4">{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>
