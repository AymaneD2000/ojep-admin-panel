<?php
include 'db_connection.php';

$sql = "SELECT * FROM pub";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listes des défilements</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <!-- Link to Bootstrap CSS for responsive design and pre-built components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h2 class="my-4">Listes des défilements</h2>
        <a href="pub_add.php" class="btn btn-success mb-3">Ajouter un défilement</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Titre</th>
                    <th>Descriptions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['logo']); ?>" width="100" alt="News Image"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['presentation']; ?></td>
                        <td>
                            <a href="pub_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="pub_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
