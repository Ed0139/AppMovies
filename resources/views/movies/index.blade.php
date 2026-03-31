<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MovieFinder</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: white;
        }

        /* HERO */

        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.9)),
                url("https://image.tmdb.org/t/p/original/5YZbUmjbMa3ClvSW1Wj3D6XGolb.jpg");
            background-size: cover;
            background-position: center;
            padding: 120px 0;
            text-align: center;
        }

        /* SEARCH */

        .search-box {
            max-width: 700px;
            margin: auto;
        }

        /* CARDS */

        .movie-card {
            transition: all .25s ease;
            border: none;
            background: #1e293b;
        }

        .movie-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
        }

        .movie-card img {
            border-radius: 6px 6px 0 0;
        }

        .rating {
            font-size: 14px;
            color: #facc15;
        }

        .year {
            font-size: 13px;
            color: #94a3b8;
        }

        .movie-card .card-body {
            color: white;
        }

        .movie-card .card-title {
            color: white;
            font-size: 16px;
            font-weight: 600;
        }

        .movie-card .year {
            color: #94a3b8;
        }

        .rating {
            color: #facc15;
            font-weight: bold;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-dark bg-dark px-4">

        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="/">
                🎬 MovieFinder
            </a>

            <div>

                @auth

                    <a href="/dashboard" class="btn btn-outline-light me-2">
                        Dashboard
                    </a>

                    <a href="/profile" class="btn btn-outline-light me-2">
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger">
                            Cerrar sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2">
                        Iniciar sesión
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-danger">
                        Registrarse
                    </a>

                @endauth

            </div>

        </div>

    </nav>
    <!-- HERO -->
    <section class="hero">

        <div class="container">

            <h1 class="display-4 fw-bold mb-3">
                🎬 MovieFinder
            </h1>

            <p class="mb-4 text-light">
                Busca millones de películas.
            </p>

            <div class="search-box row g-2">

                <div class="col-md-9">
                    <input type="text" id="searchInput" class="form-control form-control-lg"
                        placeholder="Buscar película...">
                </div>

                <div class="col-md-3">
                    <button id="searchBtn" class="btn btn-danger btn-lg w-100">
                        Buscar
                    </button>
                </div>

            </div>

        </div>
    </section>

    <!-- FILTRO DE GÉNERO -->
    <select id="genreFilter" class="form-select w-auto mb-4">

        <option value="">Todos los géneros</option>
        <option value="28">Acción</option>
        <option value="35">Comedia</option>
        <option value="27">Terror</option>
        <option value="18">Drama</option>
        <option value="16">Animación</option>
        <option value="878">Ciencia ficción</option>

    </select>

    <!-- RESULTADOS -->

    <div class="container py-5">

        <h3 class="mb-4">
            🎥 Resultados
        </h3>

        <p id="message" class="text-muted"></p>

        <div id="results" class="row"></div>

    </div>

    <div class="text-center mt-4">

        <button id="prevBtn" class="btn btn-outline-light me-2">
            ⬅ Anterior
        </button>

        <button id="nextBtn" class="btn btn-danger">
            Siguiente ➜
        </button>

    </div>
    <p class="text-center mt-3" id="pageNumber"></p>
    <script src="{{ asset('js/app.js') }}"></script>

</body>

</html>
