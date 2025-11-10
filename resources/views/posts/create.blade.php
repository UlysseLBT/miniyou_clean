<x-app-layout>
    <h1>Créer un nouveau post</h1>

    <a href="{{ route('posts.index') }}">retour à la liste</a>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <table>
            <tr>
                <th>Titre</th>
                <td>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" >
                </td>
            </tr>
            <tr>
                <th>Contenu</th>
                <td>
                    <textarea name="content" id="content">{{ old('content') }}</textarea>
                </td>
            </tr>
            <tr>
                <th>Média</th>
                <td>
                    <input type="file" name="media" id="media" >
                </td>
            </tr>
            <!-- Ajoutez d'autres champs post si nécessaire -->
            <tr>
                <td colspan="2">
                    <button type="submit">Créer</button>
                </td>
            </tr>
        </table>
    </form>
</x-app-layout>
