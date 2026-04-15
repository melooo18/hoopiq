<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - HoopIQ</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-dark text-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="#">
                <i class="bi bi-dribbble"></i> HoopIQ
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <span class="nav-link text-white">Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-warning btn-sm fw-bold" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bolder mb-3">Player Intelligence</h1>
                <p class="lead text-muted mb-4">Search any NBA Player to grab their latest stats and generate cutting-edge AI analysis with Google Gemini.</p>
                
                <form id="searchForm" class="d-flex shadow-lg rounded-pill overflow-hidden">
                    <input type="text" id="searchInput" class="form-control border-0 px-4 py-3 bg-secondary text-white" placeholder="e.g. LeBron James, Stephen Curry..." required>
                    <button type="submit" class="btn btn-warning px-5 fw-bold text-dark"><i class="bi bi-search"></i> Search</button>
                </form>
            </div>
        </div>

        <!-- Loader -->
        <div id="loader" class="text-center d-none my-5">
            <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted fw-semibold" id="loaderText">Fetching player data...</p>
        </div>

        <!-- Results Area -->
        <div id="resultsArea" class="row d-none g-4">
            <!-- Player Stats Card -->
            <div class="col-lg-5">
                <div class="card h-100 bg-secondary border-0 shadow-lg text-white premium-card">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="display-4 text-warning me-3"><i class="bi bi-person-bounding-box"></i></div>
                            <div>
                                <h3 id="playerName" class="fw-bold mb-0">Player Name</h3>
                                <p id="playerTeam" class="text-muted mb-0">Team Name</p>
                            </div>
                        </div>
                        <hr class="border-secondary mb-4">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-box p-3 bg-dark rounded text-center">
                                    <h6 class="text-muted mb-1 text-uppercase small">Position</h6>
                                    <div id="playerPosition" class="fs-4 fw-bold">N/A</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box p-3 bg-dark rounded text-center">
                                    <h6 class="text-muted mb-1 text-uppercase small">Draft Year</h6>
                                    <div id="playerDraft" class="fs-4 fw-bold">N/A</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box p-3 bg-dark rounded text-center">
                                    <h6 class="text-muted mb-1 text-uppercase small">Height</h6>
                                    <div id="playerHeight" class="fs-4 fw-bold">N/A</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box p-3 bg-dark rounded text-center">
                                    <h6 class="text-muted mb-1 text-uppercase small">Weight</h6>
                                    <div id="playerWeight" class="fs-4 fw-bold">N/A</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI AI Analysis Card -->
            <div class="col-lg-7">
                <div class="card h-100 bg-secondary border-0 shadow-lg text-white premium-card ai-glow">
                    <div class="card-body p-4 flex-column d-flex">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0 d-flex align-items-center">
                                <i class="bi bi-stars text-warning me-2"></i> Gemini AI Analysis
                            </h4>
                            <span class="badge bg-dark text-warning border border-warning px-3 py-2 rounded-pill"><i class="bi bi-lightning-charge-fill"></i> Powered by Gemini</span>
                        </div>
                        <div id="aiContent" class="flex-grow-1 p-3 bg-dark rounded">
                            <p class="text-muted text-center my-4 ai-waiting">Waiting to generate analysis...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Bootstrap & App JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js?v=3"></script>
</body>
</html>
