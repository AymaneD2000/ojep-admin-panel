<?php
// Start the session
session_start();

// If the user is already logged in, redirect them to the admin panel
if (isset($_SESSION['user_id'])) {
    header("Location: admin_panel.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php'; // Include your database connection file

    $identifier = $_POST['identifier']; // This could be either username or email
    $password = $_POST['password'];

    // Prepare the SQL query to fetch the user by username OR email
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $identifier, $identifier); // Bind the same value to both username and email
    $stmt->execute();
    $result = $stmt->get_result();

    // If a matching user is found
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify( $password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect to admin panel
            header("Location: admin_panel.php");
            exit;
        } else {
            $error = "Mot de passe incorrect!";
        }
    } else {
        $error = "Nom d'utilisateur ou email invalide!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f9;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .toggle-password {
            cursor: pointer;
            float: right;
            margin-top: -30px;
            margin-right: 10px;
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="login-container">
            <h2 class="login-header">Connexion</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label for="identifier" class="form-label">Nom d'utilisateur ou Email</label>
                    <input type="text" class="form-control" id="identifier" name="identifier" required>
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                          <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zm-8 4a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                          <path d="M8 5.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5z"/>
                        </svg>
                    </span>
                </div>
                <button type="submit" class="btn btn-primary w-100">Connexion</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const passwordToggle = document.querySelector('.toggle-password svg');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggle.setAttribute('fill', 'blue');
            } else {
                passwordField.type = 'password';
                passwordToggle.setAttribute('fill', 'currentColor');
            }
        }
    </script>
</body>
</html>
