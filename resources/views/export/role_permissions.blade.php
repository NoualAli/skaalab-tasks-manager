<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Permissions du rôle {{ $role->code }}</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
                <tr>
                    <td>{{ strtoupper($permission->code) }}</td>
                    <td>{{ $permission->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
