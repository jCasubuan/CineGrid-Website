let movies = JSON.parse(localStorage.getItem("movies")) || [];
let posters = JSON.parse(localStorage.getItem("posters")) || [];

const movieTable = document.querySelector("#movieTable tbody");
const posterInput = document.getElementById("posterInput");
const posterDropdown = document.getElementById("posterDropdown");

function renderPosterDropdown() {
    posterDropdown.innerHTML = '';
    posters.forEach((p, index) => {
        const div = document.createElement("div");
        div.innerHTML = `<img src="${p}" alt="Poster"> Poster ${index+1}`;
        div.addEventListener("click", () => {
            posterInput.value = p;
            posterDropdown.style.display = "none";
        });
        posterDropdown.appendChild(div);
    });
}

posterInput.addEventListener("click", () => {
    posterDropdown.style.display = posterDropdown.style.display === "block" ? "none" : "block";
});

document.addEventListener("click", (e) => {
    if (!e.target.closest(".poster-select")) {
        posterDropdown.style.display = "none";
    }
});

function renderTable() {
    movieTable.innerHTML = '';
    movies.forEach((movie, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td><img src="${movie.poster}" class="poster-img" alt="Poster"></td>
            <td>${movie.title}</td>
            <td>${movie.type}</td>
            <td>${movie.year}</td>
            <td>
                <button class="btn btn-warning btn-sm btn-custom" onclick="editMovie(${index})">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteMovie(${index})">Delete</button>
            </td>
        `;
        movieTable.appendChild(row);
    });
}

function showAddForm() {
    document.getElementById('form-section').style.display = 'block';
    document.getElementById('form-title').textContent = 'Add Movie/Series';
    document.getElementById('movieForm').reset();
    document.getElementById('editIndex').value = '';
    renderPosterDropdown();
}

function hideForm() {
    document.getElementById('form-section').style.display = 'none';
}

document.getElementById("movieForm").addEventListener("submit", function(e){
    e.preventDefault();
    const movie = {
        title: document.getElementById("title").value,
        type: document.getElementById("type").value,
        year: document.getElementById("year").value,
        poster: posterInput.value
    };
    const editIndex = document.getElementById("editIndex").value;
    if(editIndex === '') {
        movies.push(movie);
    } else {
        movies[editIndex] = movie;
    }
    localStorage.setItem("movies", JSON.stringify(movies));
    hideForm();
    renderTable();
});

function editMovie(index) {
    const movie = movies[index];
    document.getElementById("title").value = movie.title;
    document.getElementById("type").value = movie.type;
    document.getElementById("year").value = movie.year;
    renderPosterDropdown();
    posterInput.value = movie.poster;
    document.getElementById("editIndex").value = index;
    document.getElementById('form-title').textContent = 'Edit Movie/Series';
    document.getElementById('form-section').style.display = 'block';
}

function deleteMovie(index) {
    if(confirm('Are you sure you want to delete this movie/series?')) {
        movies.splice(index, 1);
        localStorage.setItem("movies", JSON.stringify(movies));
        renderTable();
    }
}

// Initial render
renderPosterDropdown();
renderTable();