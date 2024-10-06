<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        /* Base styling */
        body {
            padding-top: 80px; /* Adjust this dynamically */
        }

        .nav-item {
            cursor: pointer;
        }

        /* Navbar for small and large screens */
        .navbar-nav {
            display: flex;
            flex-direction: row;
        }

        /* Styling for large screens */
        .navbar-nav .nav-link {
            color: white;
            margin-right: 15px;
        }

        /* Custom styles for small screens */
        @media (max-width: 768px) {
            .navbar-nav {
                flex-direction: column;
                align-items: center;
            }
            .navbar-nav .nav-link {
                font-size: 1.25rem;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <ul class="navbar-nav ml-auto" id="navbarNav">
                <li class="nav-item">
                    <a class="nav-link active" id="nav-news" onclick="loadSection('news')">News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nav-pub" onclick="loadSection('pub')">Pub</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="nav-comment" onclick="loadSection('comment')">Comment</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container-fluid">
        <div id="main-content">
            <!-- Content will be dynamically loaded here -->
            <?php include 'news_list.php'; ?>
        </div>
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
            // Reset all nav-link classes
            document.getElementById('nav-news').classList.remove('active');
            document.getElementById('nav-pub').classList.remove('active');
            document.getElementById('nav-comment').classList.remove('active');
            
            // Set the active class for the clicked nav item
            document.getElementById('nav-' + activeSection).classList.add('active');
        }

        // Adjust padding-top of the body to ensure the navbar doesn't hide content
        function adjustPadding() {
            const navbarHeight = document.querySelector('nav').offsetHeight;
            document.body.style.paddingTop = navbarHeight + 'px';
        }

        // Run on window resize and load
        window.addEventListener('resize', adjustPadding);
        window.addEventListener('load', adjustPadding);
    </script>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
