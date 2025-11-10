<x-app-layout>
    <h1>Créer un nouvel utilisateur</h1>

    <a href="{{ route('users.index') }}">retour à la liste</a>



    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        @method('POST')
    <table>
        <tr>
            <th>ID</th>
            <td></td>
        </tr>
        <tr>
            <th>Nom</th>
            <td><input type="text" name="name" id="name" ></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><input type="email" name="email" id="email" ></td>
        </tr>
        <tr>
            <th>username</th>
            <td><input type="text" name="username" id="username" ></td>
        </tr>
        <tr>
            <th>Mot de passe</th>
            <td><input type="password" name="password" id="password" ></td>
        </tr>
        <!-- Ajoutez d'autres champs utilisateur si nécessaire -->
    </table>
        <button type="submit">Créer l'utilisateur</button>
    </form>
</x-app-layout>