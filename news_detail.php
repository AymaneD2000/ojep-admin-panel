<?php
include 'db_connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM news WHERE id=$id";
$result = $conn->query($sql);
$news = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $news['title']; ?></title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4"><?php echo $news['title']; ?></h1>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($news['image']); ?>" class="img-fluid mb-4" alt="News Image">
        <p class="lead"><?php echo $news['summary']; ?></p>
        <div><?php echo nl2br($news['content']); ?></div> <!-- Display the full content -->
        <a href="news_list.php" class="btn btn-primary mt-4">Back to News List</a>
    </div>
</body>
</html>
