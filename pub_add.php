<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $presentation = $_POST['presentation'];

    // Handle image upload as BLOB
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logo = $_FILES['logo']['tmp_name'];
        $imgContent = file_get_contents($logo); // Get image content

        // Insert the pub into the database
        $stmt = $conn->prepare("INSERT INTO pub (name, presentation, logo) VALUES (?, ?, ?)");
        $stmt->bind_param("ssb", $name, $presentation, $null); // "ssb" indicates two strings and one BLOB
        $stmt->send_long_data(2, $imgContent); // Send the image as long data

        if ($stmt->execute()) {
            // Redirect to the pub list page if successful
            header('Location: admin_panel.php');
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "No image uploaded or there was an error.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un défilement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Ajouter un défilement</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descriptions</label>
                <textarea class="form-control" name="presentation" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="logo" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
