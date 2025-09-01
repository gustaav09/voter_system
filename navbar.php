<!-- navbar.php -->
<nav class="navbar navbar-dark sticky-top" style="background-color: #FF8C00; z-index: 1030;">
    <div class="container-fluid">
        <!-- Sidebar toggle button for small screens -->
        <button class="btn btn-outline-light d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand -->
        <a class="navbar-brand fw-bold ms-2" href="#">Lapasan Profiling DB</a>

        <!-- User Icon Dropdown -->
        <div class="dropdown ms-auto">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- Bootstrap person icon -->
                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="userDropdown">
                <li><span class="dropdown-item-text">Profile</span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Make sure to include Bootstrap 5 JS & CSS and Bootstrap Icons in your <head> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
