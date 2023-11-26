<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Liste des rôles</title>
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
            @foreach ($roles as $role)
                <tr>
                    <td>{{ strtoupper($role->code) }}</td>
                    <td>{{ $role->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
