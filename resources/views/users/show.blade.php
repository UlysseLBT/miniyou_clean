<x-app-layout>
    <h1>{{ $user->name }}  {{ $user->email }}</h1>

    <p> Affichage du profil de l'utilisateur. </p>

    <a href="{{ route('users.index') }}">retour à la liste</a>

    <a href="{{ route('users.edit', $user) }}">Modifier l'utilisateur</a>

    <table>
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Nom</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>username</th>
            <td>{{ $user->username }}</td>
        </tr>
        <!-- Ajoutez d'autres champs utilisateur si nécessaire -->
    </table>
</x-app-layout>