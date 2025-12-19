// Mobile Filter Toggle
const filterToggle = document.getElementById('filterToggle');
const filterSidebar = document.getElementById('filterSidebar');
const filterOverlay = document.getElementById('filterOverlay');
const closeFilter = document.getElementById('closeFilter');

filterToggle?.addEventListener('click', () => {
    filterSidebar.classList.add('show');
    filterOverlay.classList.add('show');
});

closeFilter?.addEventListener('click', () => {
    filterSidebar.classList.remove('show');
    filterOverlay.classList.remove('show');
});

filterOverlay?.addEventListener('click', () => {
    filterSidebar.classList.remove('show');
    filterOverlay.classList.remove('show');
});

// View Toggle (Grid/List)
const gridViewBtn = document.getElementById('gridView');
const listViewBtn = document.getElementById('listView');
const movieGrid = document.getElementById('movieGrid');

gridViewBtn?.addEventListener('click', () => {
    movieGrid.classList.remove('list-view');
    movieGrid.className = 'row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 movie-grid';
    gridViewBtn.classList.add('active');
    listViewBtn.classList.remove('active');
});

listViewBtn?.addEventListener('click', () => {
    movieGrid.className = 'list-view movie-grid';
    listViewBtn.classList.add('active');
    gridViewBtn.classList.remove('active');
});

// Sort Dropdown
const sortBy = document.getElementById('sortBy');
sortBy?.addEventListener('change', (e) => {
    console.log('Sorting by:', e.target.value);
    // Here you would implement actual sorting logic
    alert('Sorting by: ' + e.target.options[e.target.selectedIndex].text);
});

// Apply Filters
const applyFilters = document.getElementById('applyFilters');
applyFilters?.addEventListener('click', () => {
    // Collect selected filters
    const filters = {
        genres: [],
        yearFrom: document.getElementById('yearFrom').value,
        yearTo: document.getElementById('yearTo').value,
        ratings: []
    };

    // Get genre checkboxes
    document.querySelectorAll('.filter-section:first-child input[type="checkbox"]:checked').forEach(cb => {
        filters.genres.push(cb.id);
    });

    // Get rating checkboxes
    document.querySelectorAll('.filter-section:nth-child(3) input[type="checkbox"]:checked').forEach(cb => {
        filters.ratings.push(cb.id);
    });

    console.log('Applied filters:', filters);
    alert('Filters applied! Check console for details.');

    // Close mobile filter
    filterSidebar.classList.remove('show');
    filterOverlay.classList.remove('show');

    // Here you would implement actual filtering logic
});

// Clear Filters
const clearFilters = document.getElementById('clearFilters');
clearFilters?.addEventListener('click', () => {
    // Uncheck all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });

    // Reset dropdowns
    document.getElementById('yearFrom').selectedIndex = 0;
    document.getElementById('yearTo').selectedIndex = 0;

    alert('All filters cleared!');
});

// Pagination
const paginationLinks = document.querySelectorAll('.pagination .page-link');
paginationLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        if (!e.target.closest('.page-item').classList.contains('disabled') &&
            e.target.textContent !== '...') {
            e.preventDefault();

            // Remove active class from all
            document.querySelectorAll('.pagination .page-item').forEach(item => {
                item.classList.remove('active');
            });

            // Add active to clicked
            e.target.closest('.page-item').classList.add('active');

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });

            console.log('Loading page:', e.target.textContent);
        }
    });
});

// Smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});