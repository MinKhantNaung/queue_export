<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

</head>

<body class="antialiased">
    <a href="{{ route('export') }}">Export Users</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Excels</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($excels as $index => $excel)
                <tr>
                    <td>{{ $excel->id }}</td>
                    <td>{{ $excel->path }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $excel->path) }}">
                            Download
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $excels->links() }}
    </div>
</body>

</html>
