// Variables globales
let currentPage = 1;
let currentQuery = "";
let timeout = null;
let currentGenre = "";

// Referencias DOM
const searchBtn = document.getElementById("searchBtn");
const searchInput = document.getElementById("searchInput");
const results = document.getElementById("results");
const message = document.getElementById("message");

// Eventos iniciales
document.addEventListener("DOMContentLoaded", cargarTrending);

searchBtn.addEventListener("click", () => {
    currentQuery = searchInput.value.trim();
    currentPage = 1;
    buscar();
});

searchInput.addEventListener("keyup", (e) => {

    if (e.key === "Enter") {
        currentQuery = searchInput.value.trim();
        currentPage = 1;
        buscar();
    }

    clearTimeout(timeout);

    timeout = setTimeout(() => {
        if (searchInput.value.length >= 3) {
            currentQuery = searchInput.value.trim();
            currentPage = 1;
            buscar();
        }
    }, 600);

});


// ==============================
// BUSCAR PELÍCULAS
// ==============================

async function buscar() {

    if (!currentQuery) {
        message.textContent = "Escribe algo para buscar.";
        results.innerHTML = "";
        return;
    }

    mostrarLoader();

    try {

        const response = await fetch(`/api/movies?q=${encodeURIComponent(currentQuery)}&page=${currentPage}&genre=${currentGenre}`);
        const data = await response.json();

        if (!data.results || data.results.length === 0) {
            message.textContent = "No se encontraron resultados.";
            results.innerHTML = "";
            return;
        }

        message.textContent = "";
        mostrarPeliculas(data.results);

    } catch (error) {

        console.error(error);
        message.textContent = "Error al conectar con el servidor.";

    }

}


// ==============================
// TRENDING
// ==============================

async function cargarTrending() {

    message.textContent = "🔥 Películas populares...";
    results.innerHTML = "";

    try {

        const response = await fetch(`/api/trending?page=${currentPage}&genre=${currentGenre}`);
        const data = await response.json();

        mostrarPeliculas(data.results);
        message.textContent = "";

    } catch (error) {

        console.error(error);
        message.textContent = "Error al cargar películas populares.";

    }

}


// ==============================
// LOADER
// ==============================

function mostrarLoader() {

    results.innerHTML = "";

    message.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-danger"></div>
            <p class="mt-2">Buscando películas...</p>
        </div>
    `;

}


// ==============================
// MOSTRAR PELÍCULAS
// ==============================

function mostrarPeliculas(peliculas) {

    results.innerHTML = "";

    peliculas.forEach(pelicula => {

        const imageUrl = pelicula.poster_path
            ? `https://image.tmdb.org/t/p/w500${pelicula.poster_path}`
            : "https://via.placeholder.com/500x750?text=Sin+Imagen";

        const year = pelicula.release_date
            ? pelicula.release_date.substring(0, 4)
            : "Sin fecha";

        const rating = pelicula.vote_average
            ? pelicula.vote_average.toFixed(1)
            : "N/A";

        results.innerHTML += `
        <div class="col-md-3 mb-4">

            <a href="/movie/${pelicula.id}" style="text-decoration:none;color:white;">

                <div class="card movie-card h-100">

                    <img src="${imageUrl}" class="card-img-top">

                    <div class="card-body">

                        <h5 class="card-title">
                        ${pelicula.title}
                        </h5>

                        <p class="year">
                        ${year}
                        </p>

                        <p class="rating">
                        ⭐ ${rating}
                        </p>

                    </div>

                </div>

            </a>

        </div>
        `;

    });

    // Mostrar número de página
    const page = document.getElementById("pageNumber");
    if (page) {
        page.textContent = "Página " + currentPage;
    }

}

// ==============================
// PAGINACIÓN
// ==============================

document.getElementById("nextBtn")?.addEventListener("click", () => {

    currentPage++;

    if (currentQuery) {
        buscar();
    } else {
        cargarTrending();
    }

});

document.getElementById("prevBtn")?.addEventListener("click", () => {

    if (currentPage > 1) {

        currentPage--;

        if (currentQuery) {
            buscar();
        } else {
            cargarTrending();
        }

    }

});

// ==============================
// FILTRO POR GENERO
// ==============================

const genreSelect = document.getElementById("genreFilter");

genreSelect?.addEventListener("change", () => {

    currentGenre = genreSelect.value;
    currentPage = 1;

    if (currentQuery) {
        buscar();
    } else {
        cargarTrending();
    }

});