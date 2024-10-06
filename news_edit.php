<?php
include 'db_connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM news WHERE id=$id";
$result = $conn->query($sql);
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
        header('Location: index.php');
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'actualités</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Link to Bootstrap CSS for responsive design and pre-built components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Modifier l'actualités</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre</label>
                <input type="text" class="form-control" name="title" value="<?php echo $news['title']; ?>" required>
            </div>
            <div class="form-group">
                <label>Sous-Titre</label>
                <textarea class="form-control" name="summary" required><?php echo $news['summary']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Contenus</label> <!-- Add content update -->
                <textarea class="form-control" name="content" rows="6" required><?php echo $news['content']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Image actuelle</label><br>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" width="100" alt="News Image">
            </div>
            <div class="form-group">
                <label>Ajouter une nouvelle image (si vous voulez)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
