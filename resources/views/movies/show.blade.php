<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>{{ $movie['title'] }}</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#0f172a;
color:white;
}

.movie-header{
padding:60px 0;
}

.poster{
border-radius:10px;
box-shadow:0 10px 30px rgba(0,0,0,0.6);
transition:0.3s;
}

.poster:hover{
transform:scale(1.03);
}

.overview{
font-size:1.1rem;
line-height:1.7;
}

.badge{
font-size:0.9rem;
margin-right:5px;
}

.back-btn{
margin-bottom:20px;
}

.trailer-section{
margin-top:60px;
}

.actor-img{
height:250px;
object-fit:cover;
border-radius:8px;
}

</style>

</head>

<body>

<div class="container movie-header">

<a href="/" class="btn btn-light back-btn">
← Volver
</a>

<div class="row align-items-start">

<div class="col-md-4">

@if($movie['poster_path'])

<img
src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
class="img-fluid poster">

@else

<img
src="https://via.placeholder.com/500x750?text=No+Image"
class="img-fluid poster">

@endif

</div>

<div class="col-md-8">

<h1 class="mb-3">{{ $movie['title'] }}</h1>

<p class="text-muted">

{{ $movie['release_date'] ?? 'Sin fecha' }} |
⭐ {{ $movie['vote_average'] }}/10

</p>
@auth

<button 
id="favoriteBtn"
class="btn btn-danger mt-3"
data-movie="{{ $movie['id'] }}"
>
❤️ Agregar a favoritos
</button>

@endauth
<div class="mb-3">

@foreach($movie['genres'] as $genre)

<span class="badge bg-danger">
{{ $genre['name'] }}
</span>

@endforeach

</div>

<p class="overview">

{{ $movie['overview'] ?: 'No hay descripción disponible.' }}

</p>

</div>

</div>


{{-- TRAILER --}}

@if($trailer)

<div class="trailer-section">

<hr class="my-5">

<h3 class="mb-4">🎬 Trailer</h3>

<div class="ratio ratio-16x9">

<iframe
src="https://www.youtube.com/embed/{{ $trailer }}"
title="Trailer"
allowfullscreen>
</iframe>

</div>

</div>

@endif


{{-- CAST --}}

@if(!empty($cast))

<hr class="my-5">

<h3 class="mb-4">🎭 Reparto</h3>

<div class="row">

@foreach(array_slice($cast,0,8) as $actor)

<div class="col-md-3 col-6 mb-4 text-center">

<img 
src="{{ $actor['profile_path'] ? 'https://image.tmdb.org/t/p/w200'.$actor['profile_path'] : 'https://via.placeholder.com/200x300?text=Actor' }}"
class="img-fluid actor-img shadow">

<h6 class="mt-2">
{{ $actor['name'] }}
</h6>

<p style="font-size:14px;color:gray;">
{{ $actor['character'] }}
</p>

</div>

@endforeach

</div>

@endif


</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

const btn = document.getElementById("favoriteBtn");

if(!btn) return;

btn.addEventListener("click", async function(){

const movieId = btn.dataset.movie;

try{

const response = await fetch("/favorite",{

method:"POST",

headers:{
"Content-Type":"application/json",
"X-CSRF-TOKEN":"{{ csrf_token() }}",
"Accept":"application/json"
},

body:JSON.stringify({
movie_id:movieId,
title:"{{ $movie['title'] }}",
poster:"{{ $movie['poster_path'] }}"
})

});

const data = await response.json();

if(data.status === "added"){

btn.innerHTML="🤍 Quitar de favoritos";
btn.classList.remove("btn-danger");
btn.classList.add("btn-secondary");

}

if(data.status === "removed"){

btn.innerHTML="❤️ Agregar a favoritos";
btn.classList.remove("btn-secondary");
btn.classList.add("btn-danger");

}

}catch(e){

console.log("Error:",e);

}

});

});

</script>
</body>
</html>