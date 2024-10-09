<?php
include 'db_connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM pub WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id); // "i" for integer (id)
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $presentation = $_POST['presentation'];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logo = $_FILES['logo']['tmp_name'];
        $imgContent = file_get_contents($logo);

        // Update pub with a new image
        $stmt = $conn->prepare("UPDATE pub SET name=?, presentation=?, logo=? WHERE id=?");
        $stmt->bind_param("ssbi", $name, $presentation, $null, $id);
        $stmt->send_long_data(2, $imgContent); // Send the BLOB image data
    } else {
        // Update pub without changing the image
        $stmt = $conn->prepare("UPDATE pub SET name=?, presentation=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $presentation, $id); // Bind parameters
    }

    if ($stmt->execute()) {
        header('Location: admin_panel.php');
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le défilement</title>
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
        .current-image {
            display: block;
            margin: 20px auto;
            max-width: 100%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier le défilement</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($news['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descriptions</label>
                <textarea class="form-control" name="presentation" rows="4" required><?php echo htmlspecialchars($news['presentation']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image actuelle</label><br>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['logo']); ?>" class="current-image" alt="Image de la publicité">
            </div>
            <div class="mb-3">
                <label class="form-label">Ajouter une nouvelle image (si vous voulez)</label>
                <input type="file" class="form-control" name="logo" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="admin_panel.php" class="btn btn-secondary mt-2">Retour</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
