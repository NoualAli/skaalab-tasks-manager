<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liste des utilisateurs</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Nom d'utilisateur</th>
                <th>Genre</th>
                <th>Nom complet</th>
                <th>Adresse e-mail</th>
                <th>N° de téléphone</th>
                <th>Rôle</th>
                <th>Actif</th>
                <th>Date de création</th>
                <th>Dernière connexion</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->gender_str }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?: '-' }}</td>
                    <td>{{ strtoupper($user->role->code) . ' - ' . $user->role->name }}</td>
                    <td>{{ $user->is_active ? 'Oui' : 'Non' }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->last_login?->getAttributes()['last_activity'] ? \Carbon\Carbon::parse($user->last_login->getAttributes()['last_activity'])->format('d-m-Y H:i:s') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
