<!-- PHP connection -->
<?php
require_once 'includes/init.php';

// error handling for unauthorized access of admin.php from the url
if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['user_role']) ||
    $_SESSION['user_role'] !== 'admin'
) {
    header('Location: index.php?error=unauthorized');
    exit;
}

// Fetch movie count
$movieCount = $Conn->query("SELECT COUNT(*) as total FROM movies")->fetch_assoc()['total'];

// // Fetch Series Count (if you have a series table, otherwise set to 0)
// $seriesCount = $Conn->query("SELECT COUNT(*) as total FROM series")->fetch_assoc()['total'];

// Fetch User Count
$userCount = $Conn->query("SELECT COUNT(*) as total FROM users WHERE role != 'admin'")->fetch_assoc()['total'];

// // Fetch Review Count (assuming you have a reviews table)
// $reviewCount = $Conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'];

// to get the 5 recent activity
$recentActivity = $Conn->query("
    SELECT title, created_at 
    FROM movies 
    ORDER BY created_at DESC 
    LIMIT 5
");

// Pagination Logic
$limit = 5; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total movies for pagination count
$total_res = $Conn->query("SELECT COUNT(*) as count FROM movies");
$total_movies = $total_res->fetch_assoc()['count'];
$total_pages = ceil($total_movies / $limit);

// Fetch only 5 movies for the current page 
// Note: We JOIN movie_genres and genres to get the names
$movies_query = "SELECT m.*, GROUP_CONCAT(g.name SEPARATOR ', ') as genre_names 
                 FROM movies m 
                 LEFT JOIN movie_genres mg ON m.movie_id = mg.movie_id 
                 LEFT JOIN genres g ON mg.genre_id = g.genre_id 
                 GROUP BY m.movie_id 
                 ORDER BY m.movie_id DESC 
                 LIMIT $limit OFFSET $offset";
$movies = $Conn->query($movies_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CineGrid Admin Dashboard - Manage movies, series, and users">
    <title>CineGrid | <?php echo ucfirst($current_page); ?></title>

    <!-- Site Icon / Logo -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/svg+xml" href="assets/img/logo.svg">

    <!-- BootStrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Boostrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/admin-dashboard.css">

</head>

<body>
    <!-- Sidebar -->
    <?php include 'includes/admin-sidebar.php'; ?>

    <!-- Mobile Toggle -->
    <button class="mobile-toggle" id="mobileToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Main Content -->
    <main class="main-content">

        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h4 class="mb-0">Welcome, Admin</h4>
                <small class="text-white">Manage your CineGrid content</small>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <button class="btn btn-outline-light btn-sm">
                    <i class="bi bi-bell"></i>
                </button>

                <!-- Logout button -->
                <button class="btn btn-outline-danger btn-sm" 
                        data-bs-toggle="modal" 
                        data-bs-target="#logoutConfirmModal">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>

        <!-- Dashboard Section -->
        <section id="dashboardSection" class="content-section">
            <h2 class="mb-4">Dashboard Overview</h2>

            <!-- Stats Cards -->
            <!-- Movie Card -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(52, 152, 219, 0.2);">
                            <i class="bi bi-film" style="color: #3498db;"></i>
                        </div>
                        <div class="stat-value"><?php echo $movieCount; ?></div>
                        <div class="stat-label">Total Movies</div>
                    </div>
                </div>
                <!-- Series Card -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(46, 204, 113, 0.2);">
                            <i class="bi bi-tv" style="color: #2ecc71;"></i>
                        </div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Total Series</div>
                    </div>
                </div>
                <!-- Total Users Card -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(155, 89, 182, 0.2);">
                            <i class="bi bi-people" style="color: #9b59b6;"></i>
                        </div>
                        <div class="stat-value"><?php echo $userCount; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <!-- Total Review Card -->
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(241, 196, 15, 0.2);">
                            <i class="bi bi-chat-square-text" style="color: #f1c40f;"></i>
                        </div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Total Reviews</div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="data-table">
                <div class="p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Item</th>
                            <th>User</th>
                            <th>Date Added</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recentActivity->num_rows > 0): ?>
                            <?php while($row = $recentActivity->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="badge bg-success">Added</span></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td>CineGrid Admin</td>
                                    <td>
                                        <?php 
                                            // Formats the timestamp to something like "Dec 29, 2025"
                                            echo date('M d, Y', strtotime($row['created_at'])); 
                                        ?>
                                    </td>
                                    <td><span class="status-badge status-active">Success</span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-white">No recent activity found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Movies Section -->
        <section id="moviesSection" class="content-section" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Manage Movies</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMovieModal">
                    <i class="bi bi-plus-lg me-2"></i>Add New Movie
                </button>
            </div>

            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="search-bar">
                        <input type="text" id="movieSearch" placeholder="Search movies..." class="form-control">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="movieGenreFilter">
                        <option value="">All Genres</option>
                        <option>Action</option>
                        <option>Drama</option>
                        <option>Comedy</option>
                        <option>Sci-Fi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="movieYearFilter">
                        <option value="">All Years</option>
                        <option>2024</option>
                        <option>2023</option>
                        <option>2022</option>
                    </select>
                </div>
            </div>

            <!-- Movies Table -->
            <div class="data-table">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th>Ratings</th>
                            <th>Featured</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="moviesTableBody">
                        <?php if ($movies->num_rows > 0): ?>
                            <?php while($row = $movies->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?= str_pad($row['movie_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                    <td>
                                        <img src="<?= $row['poster_path'] ?: 'assets/img/no-poster.jpg'; ?>" 
                                            class="thumbnail" alt="Poster" style="width:50px; height:75px; object-fit:cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($row['title']); ?></div>
                                        <small class="text-white"><?= ucfirst($row['type']); ?></small>
                                    </td>
                                    <td><span class="small"><?= htmlspecialchars($row['genre_names'] ?: 'N/A'); ?></span></td>
                                    <td><?= $row['release_year']; ?></td>                                
                                    <td>
                                        <span class="badge bg-dark text-warning border border-warning">
                                            <i class="bi bi-star-fill me-1"></i><?= $row['rating']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm <?= $row['is_featured'] ? 'btn-warning' : 'btn-outline-secondary' ?>" 
                                                onclick="toggleFeatured(<?= $row['movie_id']; ?>)" 
                                                title="Toggle Hero Banner">
                                            <i class="bi <?= $row['is_featured'] ? 'bi-lightning-fill' : 'bi-lightning' ?>"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="action-btn btn-view" onclick="viewMovie(<?= $row['movie_id']; ?>)">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editMovieModal">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="action-btn btn-delete" onclick="deleteMovie(<?= $row['movie_id']; ?>, '<?= addslashes($row['title']); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-white">No movies found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
                    </li>

                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </section>

        <!-- Series Section -->
        <section id="seriesSection" class="content-section" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Manage Series</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSeriesModal">
                    <i class="bi bi-plus-lg me-2"></i>Add New Series
                </button>
            </div>

            <!-- Search Bar -->
            <div class="search-bar mb-4">
                <input type="text" id="seriesSearch" placeholder="Search series..." class="form-control">
                <i class="bi bi-search"></i>
            </div>

            <!-- Series Table -->
            <div class="data-table">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Seasons</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td><img src="https://via.placeholder.com/50x75/667eea/ffffff?text=BB" class="thumbnail" alt="Series"></td>
                            <td>Breaking Bad</td>
                            <td>Crime</td>
                            <td>5</td>
                            <td><i class="bi bi-star-fill text-warning"></i> 9.5</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewSeries(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editSeriesModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteSeries(1)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td><img src="https://via.placeholder.com/50x75/764ba2/ffffff?text=GOT" class="thumbnail" alt="Series"></td>
                            <td>Game of Thrones</td>
                            <td>Fantasy</td>
                            <td>8</td>
                            <td><i class="bi bi-star-fill text-warning"></i> 9.2</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewSeries(2)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editSeriesModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteSeries(2)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Users Section -->
        <section id="usersSection" class="content-section" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Manage Users</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-lg me-2"></i>Add New User
                </button>
            </div>

            <!-- Search Bar -->
             <div class="search-bar mb-4">
                <input type="text" id="userSearch" placeholder="Search users..." class="form-control">
                <i class="bi bi-search"></i>
            </div>

            <!-- Users Table -->
            <div class="data-table">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td><span class="badge bg-primary">User</span></td>
                            <td>Jan 15, 2024</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewUser(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteUser(1)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>Jane Smith</td>
                            <td>jane@example.com</td>
                            <td><span class="badge bg-success">Admin</span></td>
                            <td>Feb 20, 2024</td>
                            <td><span class="status-badge status-active">Active</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewUser(2)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteUser(2)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>003</td>
                            <td>Mike Wilson</td>
                            <td>mike@example.com</td>
                            <td><span class="badge bg-primary">User</span></td>
                            <td>Mar 10, 2024</td>
                            <td><span class="status-badge status-inactive">Inactive</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewUser(3)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-edit" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteUser(3)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Reviews Section -->
         <section id="reviewsSection" class="content-section" style="display: none;">
            <h2 class="mb-4">Manage Reviews</h2>

            <!-- Search Bar -->
           <div class="search-bar mb-4">
                <input type="text" id="reviewSearch" placeholder="Search reviews..." class="form-control">
                <i class="bi bi-search"></i>
            </div>

            <!-- Reviews Table -->
            <div class="data-table">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Content</th>
                            <th>Rating</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>001</td>
                            <td>john_doe_92</td>
                            <td>The Dark Knight</td>
                            <td><i class="bi bi-star-fill text-warning"></i> 10/10</td>
                            <td>Dec 1, 2024</td>
                            <td><span class="status-badge status-active">Approved</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewReview(1)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteReview(1)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>002</td>
                            <td>movie_buff</td>
                            <td>Breaking Bad</td>
                            <td><i class="bi bi-star-fill text-warning"></i> 9/10</td>
                            <td>Dec 5, 2024</td>
                            <td><span class="status-badge status-active">Approved</span></td>
                            <td>
                                <button class="action-btn btn-view" onclick="viewReview(2)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="action-btn btn-delete" onclick="deleteReview(2)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Analytics Section -->
        <section id="analyticsSection" class="content-section" style="display: none;">
            <h2 class="mb-4">Analytics</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="data-table p-4">
                        <h5 class="mb-3">Most Viewed Content</h5>
                        <ol>
                            <li class="mb-2">The Dark Knight - 2.5M views</li>
                            <li class="mb-2">Breaking Bad - 5.2M views</li>
                            <li class="mb-2">Inception - 1.8M views</li>
                            <li class="mb-2">Game of Thrones - 4.1M views</li>
                            <li class="mb-2">Stranger Things - 3.3M views</li>
                        </ol>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="data-table p-4">
                        <h5 class="mb-3">Top Rated Content</h5>
                        <ol>
                            <li class="mb-2">Breaking Bad - 9.5/10</li>
                            <li class="mb-2">The Wire - 9.3/10</li>
                            <li class="mb-2">Game of Thrones - 9.2/10</li>
                            <li class="mb-2">The Dark Knight - 9.0/10</li>
                            <li class="mb-2">Inception - 8.8/10</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Settings Section -->
        <section id="settingsSection" class="content-section" style="display: none;">
            <h2 class="mb-4">Settings</h2>
            <div class="data-table p-4">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" class="form-control" value="CineGrid">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Email</label>
                        <input type="email" class="form-control" value="admin@cinegrid.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Items Per Page</label>
                        <select class="form-select">
                            <option>10</option>
                            <option selected>20</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenanceMode">
                            <label class="form-check-label" for="maintenanceMode">
                                Maintenance Mode
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </section>
    </main>
    
    <?php 
        include 'includes/admin-modals/movie-modals.php'; 
        include 'includes/admin-modals/series-modals.php';
        include 'includes/admin-modals/user-modals.php';
    ?>

    <?php include 'includes/footer.php'; ?>