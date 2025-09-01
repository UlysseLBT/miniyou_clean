<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes médias</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <form action="{{ route('media.store') }}" method="post" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded border">
                @csrf
                <input type="file" name="file" required class="block">
                @error('file') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                <button class="mt-3 inline-flex items-center rounded border px-3 py-1">Uploader</button>
            </form>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($items as $m)
                    <div class="border bg-white p-2 rounded">
                        <img src="{{ Storage::url($m->path) }}" alt="" class="w-full h-40 object-cover rounded">
                        <div class="text-sm mt-1 truncate">{{ $m->original_name }}</div>
                        <form action="{{ route('media.destroy',$m) }}" method="post" class="mt-2">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm">Supprimer</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>
