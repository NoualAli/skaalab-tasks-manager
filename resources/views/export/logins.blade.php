<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Authentifications {{ $user->id }}</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Langue</th>
                <th>Robot</th>
                <th>Appareil</th>
                <th>Navigateur</th>
                <th>Version navigateur</th>
                <th>Platforme</th>
                <th>Version plateforme</th>
                <th>Activité</th>
                <th>Adresse ip</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logins as $login)
                <tr>
                    <td>{{ $login->language ?: '-' }}</td>
                    <td>{{ $login->robot ?: '-' }}</td>
                    <td>{{ $login->device }}</td>
                    <td>{{ $login->browser }}</td>
                    <td>{{ $login->browser_version }}</td>
                    <td>{{ $login->platform }}</td>
                    <td>{{ $login->platform_version }}</td>
                    <td>{{ $login?->getAttributes()['last_activity'] ? \Carbon\Carbon::parse($login->getAttributes()['last_activity'])->format('d-m-Y H:i:s') : '-' }}
                    <td>{{ $login->ip_address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
