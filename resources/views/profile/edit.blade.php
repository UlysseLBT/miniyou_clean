<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mon profil</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="mb-4 rounded bg-green-100 p-3 text-green-800">{{ session('status') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" class="space-y-4 bg-white p-4 rounded border">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-medium">Display name</label>
                    <input class="mt-1 border rounded p-2 w-full" name="display_name" value="{{ old('display_name',$profile->display_name) }}" />
                    @error('display_name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Bio</label>
                    <textarea class="mt-1 border rounded p-2 w-full" name="bio">{{ old('bio',$profile->bio) }}</textarea>
                    @error('bio') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium">Avatar</label>
                    <input type="file" name="avatar" class="mt-1" />
                    @if($profile->avatar_path)
                        <img src="{{ Storage::url($profile->avatar_path) }}" class="h-20 mt-2 rounded" />
                    @endif
                    @error('avatar') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                </div>

                <button class="inline-flex items-center rounded border px-3 py-1">Enregistrer</button>
            </form>
        </div>
    </div>
</x-app-layout>
