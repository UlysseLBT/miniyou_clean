<x-app-layout>
    <h1>{{ $user->name }}  {{ $user->email }}</h1>

    <p> Affichage du profil de l'utilisateur. </p>

    <a href="{{ route('users.index') }}">retour à la liste</a>

    <a href="{{ route('users.edit', $user) }}">Modifier l'utilisateur</a>

    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
    <table>
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Nom</th>
            <td><input type="text" name="name" id="name" value="{{ $user->name }}"></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input type="email" name="email" id="email" value="{{ $user->email }}"></td>
        </tr>
        <tr>
            <th>username</th>
            <td><input type="text" name="username" id="username" value="{{ $user->username }}"></td>
        </tr>
        <!-- Ajoutez d'autres champs utilisateur si nécessaire -->
    </table>
        <button type="submit">Mettre à jour l'utilisateur</button>
    </form>
</x-app-layout>