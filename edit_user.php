<?php
session_start();

// Check if the user has the right role (Superuser or Admin)
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 1 && $_SESSION['role'] != 2)) {
    header("Location: admin_panel.php");
    exit;
}

// Database connection
require 'db_connection.php';

// Get user ID from the URL
$user_id = $_GET['id'];

// Fetch the user details
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated data from the form
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $role = intval($_POST['role']);
    
    // Check if a new password was entered
    $password = $_POST['password'];
    $update_sql = "UPDATE users SET name = ?, email = ?, role = ? " . (empty($password) ? "" : ", password = ?") . " WHERE id = ?";
    
    // Prepare the statement
    $update_stmt = $conn->prepare($update_sql);
    
    if (empty($password)) {
        // If no password was provided, don't update the password field
        $update_stmt->bind_param('ssii', $name, $email, $role, $user_id);
    } else {
        // Hash the new password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt->bind_param('ssisi', $name, $email, $role, $hashed_password, $user_id);
    }

    if ($update_stmt->execute()) {
        header("Location: admin_panel.php?update=success");
        exit;
    } else {
        $error_message = "Erreur lors de la mise à jour. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4 text-center">Modifier Utilisateur</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe (laisser vide pour conserver l'ancien)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <select class="form-control" id="role" name="role" required>
                <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Superuser</option>
                <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>Admin</option>
                <option value="3" <?php echo $user['role'] == 3 ? 'selected' : ''; ?>>User</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="admin_panel.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
