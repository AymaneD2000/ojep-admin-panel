<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

ini_set('session.gc_maxlifetime', 1440); // 1440 seconds = 24 minutes
session_start();
session_regenerate_id(true);
//Check if the user is logged in
if (!isset($_SESSION['user_id'])) { // Change 'user_id' to your session variable that indicates a logged-in user
    header("Location: index.php");
    exit;
}

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
// Check the user's role
$user_role = $_SESSION['role']; // This should be set when the user logs in
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'Administration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 56px; /* Adjust this dynamically based on navbar height */
        }

        /* Navbar styling */
        .navbar-dark .navbar-brand {
            font-weight: bold;
            letter-spacing: 0.05em;
        }

        .navbar-dark .nav-link {
            color: #fff;
            transition: color 0.3s ease;
        }

        .navbar-dark .nav-link:hover,
        .navbar-dark .nav-link.active {
            color: #f8f9fa;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-nav {
                flex-direction: column;
            }

            .navbar-nav .nav-link {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }
        }

        /* Content Area */
        #main-content {
            margin-top: 2rem;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" id="nav-news" onclick="loadSection('news')">Actualités</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-pub" onclick="loadSection('pub')">Défilement</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="nav-comment" onclick="loadSection('comment')">Commentaires</a>
                    </li>
                    <?php if ($user_role == 1 || $user_role == 2): ?>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-users" onclick="loadSection('users')">Gestion des Utilisateurs</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nav-register" onclick="loadSection('register')">Enregistrer un Utilisateur</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion (<?php echo $_SESSION['name']; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid" id="main-content">
        <h1 class="text-center mt-4">Bienvenue, <?php echo $_SESSION['name']; ?>!</h1>
        <!-- Content will be dynamically loaded here -->
        <?php include 'news_list.php'; ?>
    </div>

    <!-- JavaScript to dynamically load sections and adjust the navbar -->
    <script>
        function loadSection(section) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', section + '_list.php', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('main-content').innerHTML = xhr.responseText;
                    updateActiveNav(section);
                }
            };
            xhr.send();
        }

        function updateActiveNav(activeSection) {
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            navLinks.forEach(link => link.classList.remove('active'));
            document.getElementById('nav-' + activeSection).classList.add('active');
        }

        function adjustPadding() {
            const navbarHeight = document.querySelector('nav').offsetHeight;
            document.body.style.paddingTop = navbarHeight + 'px';
        }

        window.addEventListener('resize', adjustPadding);
        window.addEventListener('load', adjustPadding);
    </script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
