<!DOCTYPE html>
<html>

<head>

    <title>Mis favoritos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0f172a;
            color: white;
        }

        .card {
            background: #1e293b;
            border: none;
        }

        .movie-card img {
            border-radius: 8px;
        }
    </style>

</head>

<body>

    <div class="container py-5">

        <a href="/" class="btn btn-light mb-4">
            ← Volver al inicio
        </a>

        <h2 class="mb-4">❤️ Mis películas favoritas</h2>

        <div class="row">

            @forelse($favorites as $movie)
                <div class="col-md-3 mb-4">

                    <div class="card movie-card bg-dark text-white">

                        <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster }}" class="img-fluid">

                        <div class="card-body">

                            <h6 class="text-white">{{ $movie->title }}</h6>
                            <button class="btn btn-danger btn-sm remove-fav-btn mt-2" data-id="{{ $movie->movie_id }}">
                                ❌ Quitar
                            </button>
                        </div>
                    </div>
                </div>

            @empty

                <p>No tienes favoritos aún.</p>
            @endforelse

        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            document.querySelectorAll(".remove-fav-btn").forEach(btn => {

                btn.addEventListener("click", async () => {

                    const movieId = btn.dataset.id;

                    try {

                        const response = await fetch("/favorite", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                movie_id: movieId
                            })
                        });

                        const data = await response.json();

                        if (data.status === "removed") {
                            btn.closest(".col-md-3").remove();
                        }

                    } catch (error) {
                        console.error(error);
                    }

                });

            });

        });
    </script>
</body>

</html>
