<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Створення категорії</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
<?php
$name = "";
$image = "";
$description = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    if (!empty($name) && !empty($image) && !empty($description)) {
        $sql = "INSERT INTO tbl_categories (name, image, description) VALUES ('$name', '$image', '$description')";//sql запит
        $dbh->query($sql);//запускаємо sql запит на нашій бд
        header("Location: /");
        exit;
    }
}
?>
<main>
    <div class="container">
        <h1 class="text-center">Додати категорію</h1>
        <form method="post" class="needs-validation" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Назва</label>
                <input type="text" class="form-control" value="<?php echo $name?>" id="name" name="name" required>
                <div class="invalid-feedback">
                    Вкажіть назву категорії.
                </div>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">URL фото</label>
                <input type="text" class="form-control" value="<?php echo $image?>" id="image" name="image" required>
                <div class="invalid-feedback">
                    Вкажіть шлях до фото для категорії.
                </div>
            </div>

            <div class="mb-3">
                <div class="form-floating">
                    <textarea class="form-control"
                              name="description"
                              placeholder="Leave a comment here"
                              id="description" required
                              style="height: 100px"><?php echo $description?></textarea>
                    <div class="invalid-feedback">
                        Вкажіть опис категорії.
                    </div>
                    <label for="description">Опис</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Додати</button>
        </form>

    </div>
</main>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/bootstrap.validation.js"></script>
</body>
</html>

