let movies = JSON.parse(localStorage.getItem("movies")) || [];
let posters = JSON.parse(localStorage.getItem("posters")) || [];

const posterInput = document.getElementById("poster");
const posterPreview = document.getElementById("posterPreview");

// Show image preview when file is selected
posterInput.addEventListener("change", function() {
    const file = this.files[0];
    if(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            posterPreview.src = e.target.result;
            posterPreview.style.display = "block";
        }
        reader.readAsDataURL(file);
    } else {
        posterPreview.style.display = "none";
    }
});

document.getElementById("addForm").addEventListener("submit", function(e){
    e.preventDefault();
    const title = document.getElementById("title").value;
    const type = document.getElementById("type").value;
    const year = document.getElementById("year").value;
    const file = posterInput.files[0];

    if(!file) return alert("Please select a poster image!");

    const reader = new FileReader();
    reader.onload = function(e){
        const posterData = e.target.result; // Base64 string

        const newMovie = { title, type, year, poster: posterData };
        movies.push(newMovie);
        localStorage.setItem("movies", JSON.stringify(movies));

        // Add to posters array if not exists
        if(!posters.includes(posterData)) {
            posters.push(posterData);
            localStorage.setItem("posters", JSON.stringify(posters));
        }

        alert("Movie/Series added successfully!");
        document.getElementById("addForm").reset();
        posterPreview.style.display = "none";
    }
    reader.readAsDataURL(file);
});