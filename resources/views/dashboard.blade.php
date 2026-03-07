<!DOCTYPE html>
<html>

<head>

<title>Mis favoritos</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#0f172a;
color:white;
}

.card{
background:#1e293b;
border:none;
}

.movie-card img{
border-radius:8px;
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

<div class="card movie-card">

<img
src="https://image.tmdb.org/t/p/w500{{ $movie->poster }}"
class="img-fluid">

<div class="card-body">

<h6>{{ $movie->title }}</h6>

</div>

</div>

</div>

@empty

<p>No tienes favoritos aún.</p>

@endforelse

</div>

</div>

</body>
</html>