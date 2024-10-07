<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];  // Add content field

    // Handle image upload as BLOB
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['tmp_name'];
        $imgContent = file_get_contents($image);

        // Insert the news into the database
        $stmt = $conn->prepare("INSERT INTO news (title, summary, content, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssb", $title, $summary, $content, $null);
        $stmt->send_long_data(3, $imgContent);

        if ($stmt->execute()) {
            header('Location: admin_panel.php');
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "No image uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une actualités</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Link to Bootstrap CSS for responsive design and pre-built components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Ajouter une actualités</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="form-group">
                <label>Sous-titre</label>
                <textarea class="form-control" name="summary" required></textarea>
            </div>
            <div class="form-group">
                <label>Contenu</label> <!-- Add content input field -->
                <textarea class="form-control" name="content" rows="6" required></textarea>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" class="form-control" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
