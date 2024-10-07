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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update user in the database
    $update_sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssii', $name, $email, $role, $user_id);
    if ($update_stmt->execute()) {
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>

<div class="container">
    <h2>Modifier Utilisateur</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Rôle</label>
            <select class="form-control" id="role" name="role" required>
                <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Superuser</option>
                <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>Admin</option>
                <option value="3" <?php echo $user['role'] == 3 ? 'selected' : ''; ?>>User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>

<?php
$stmt->close();
$conn->close();
?>
