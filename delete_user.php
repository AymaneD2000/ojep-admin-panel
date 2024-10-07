<?php
session_start();

// Check if the user has the right role (Superuser)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: admin_panel.php");
    exit;
}

// Database connection
require 'db_connection.php';

// Get user ID from the URL
$user_id = $_GET['id'];

// Prevent the deletion of the superuser
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] == 1) {
    echo "Vous ne pouvez pas supprimer le superutilisateur.";
} else {
    // Delete user from the database
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('i', $user_id);

    if ($delete_stmt->execute()) {
        header("Location: admin_panel.php");
        exit;
    } else {
        echo "Erreur lors de la suppression.";
    }
}

$stmt->close();
$conn->close();
?>
