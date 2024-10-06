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
        $stmt->bind_param("ssb", $name, $presentation, $null);
        $stmt->send_long_data(2, $imgContent); // Send the BLOB image data
        $stmt->bind_param("ssi", $name, $presentation, $id); // Bind parameters for the update
    } else {
        // Update pub without changing the image
        $stmt = $conn->prepare("UPDATE pub SET name=?, presentation=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $presentation, $id); // Bind parameters
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
    <title>Modifier publiciter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Link to Bootstrap CSS for responsive design and pre-built components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Modifier la publicité</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Titre</label>
                <input type="text" class="form-control" name="name" value="<?php echo $news['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Descriptions</label>
                <textarea class="form-control" name="presentation" required><?php echo $news['presentation']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Image Acuelle</label><br>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($news['logo']); ?>" width="100" alt="News Image">
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
