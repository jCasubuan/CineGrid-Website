
const modeToggle = document.getElementById('modeToggle');
const body = document.body;
const LIGHT_MODE_CLASS = 'light-mode'; 


    if (localStorage.getItem('theme') === 'light') {
        body.classList.add(LIGHT_MODE_CLASS);
        modeToggle.innerHTML = '<i class="bi bi-moon-fill me-0.5"></i>'; 
    } else {
        modeToggle.innerHTML = '<i class="bi bi-sun-fill me-0.5"></i>';
    }


    modeToggle.addEventListener('click', () => {
        body.classList.toggle(LIGHT_MODE_CLASS);

        if (body.classList.contains(LIGHT_MODE_CLASS)) {
            modeToggle.innerHTML = '<i class="bi bi-moon-fill me-0.5"></i>'; 
            localStorage.setItem('theme', 'light');
        } else {
            modeToggle.innerHTML = '<i class="bi bi-sun-fill me-0.5"></i>'; 
            localStorage.setItem('theme', 'dark');
        }
});