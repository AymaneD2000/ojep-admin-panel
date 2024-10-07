<?php
include 'db_connection.php';

$id = $_GET['id'];
$sql = "DELETE FROM pub WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    header('Location: admin_panel.php');
} else {
    echo "Error: " . $conn->error;
}
?>
