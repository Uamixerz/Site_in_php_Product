<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Редагування елементу</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
<?php
$IDelement = $_GET['element'];
$sql = "Select * from tbl_categories WHERE id = $IDelement;";
$command = $dbh->query($sql);
foreach ($command as $row){
$name = $row["name"];
$image = $row["image"];
$description = $row["description"];
$id = $row["id"];}
?>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $description = $_POST['description'];

    $sql = "UPDATE tbl_categories SET name = '$name', image='$image', description='$description' WHERE id = $id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    header("Location: /");
    exit;
}

?>
<main>
    <div class="container">
        <h1 class="text-center">Додати категорію</h1>
        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label" >Назва</label>
                <input type="text" class="form-control" value="<?php echo $name?>" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">URL фото</label>
                <input type="text" class="form-control" value="<?php echo $image?>" id="image" name="image">
            </div>

            <div class="mb-3">
                <div class="form-floating">
                    <textarea class="form-control"
                              name="description"
                              placeholder="Leave a comment here"
                              id="description"
                              style="height: 100px"><?php echo $description ?></textarea>
                    <label for="description">Опис</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Змінити</button>
        </form>

    </div>
</main>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
