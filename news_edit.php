<?php
include 'db_connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM news WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $summary = $_POST['summary'];
    $content = $_POST['content'];  // Add content update

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['tmp_name'];
        $imgContent = file_get_contents($image);

        // Update news with new image
        $stmt = $conn->prepare("UPDATE news SET title=?, summary=?, content=?, image=? WHERE id=?");
        $stmt->bind_param("ssbsi", $title, $summary, $content, $imgContent, $id);
    } else {
        // Update news without changing the image
        $stmt = $conn->prepare("UPDATE news SET title=?, summary=?, content=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $summary, $content, $id);
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
    <title>Modifier l'Actualité</title>
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
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier l'Actualité</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Sous-Titre</label>
                <textarea class="form-control" name="summary" required><?php echo htmlspecialchars($news['summary']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Contenu</label>
                <textarea class="form-control" name="content" rows="6" required><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            <div class="current-image">
                <label>Image actuelle</label><br>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" width="100" alt="Image d'actualité">
            </div>
            <div class="mb-3">
                <label class="form-label">Ajouter une nouvelle image (si vous voulez)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="admin_panel.php" class="btn btn-secondary mt-2">Retour</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
