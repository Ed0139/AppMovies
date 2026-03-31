<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>

    <style>
        body {
            font-family: Arial;
            margin: 0;
        }

        nav {
            background: #333;
            padding: 15px;
        }

        nav a {
            color: white;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            padding: 20px;
        }
    </style>
</head>

<body>

    <nav>
        <a href="{{ route('admin.users.index') }}">Usuarios</a>
    </nav>

    <div class="container">
        @yield('content')
    </div>

</body>

</html>
