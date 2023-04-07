<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Створення категорії</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
<?php
$uploadDirectory = __DIR__ . '/../dataImages/';
$saveUrl = '/dataImages/';
$name = "";
$image = "";
$description = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    if (!empty($name) && !empty($image) && !empty($description)) {
        $fileName = time() . "_" . rand(1000, 99999) . "_" . $image["name"]; // генеруємо унікальне ім'я файлу
        $fileTmpName = $image["tmp_name"];
        $tempUrlSave = $saveUrl . $fileName;
        $filePath = $uploadDirectory . $fileName;
        //Зберігання на сервер
        move_uploaded_file($fileTmpName, $filePath);
        //Зберігання до бази данних
        $sql = "INSERT INTO tbl_categories (name, image, description) VALUES ('$name', '$tempUrlSave', '$description')";//sql запит
        $dbh->query($sql);//запускаємо sql запит на нашій бд

        echo '<script>window.location.replace("' . "/" . '");</script>';
        exit;
    }
}
?>
<main>
    <div class="container">
        <h1 class="text-center">Додати категорію</h1>
        <form method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Назва</label>
                <input type="text" class="form-control" value="<?php echo $name ?>" id="name" name="name" required>
                <div class="invalid-feedback">
                    Вкажіть назву категорії.
                </div>
            </div>
            <div class="mb-3">
                <div id="input-list">
                    <label for="input-1">
                        <img src="/dataImages/upload.jpg" style="cursor:pointer;" alt="Фото товару"
                             width="120px">
                    </label>
                    <input type="file" name="image" class="d-none inputImg" id="input-1" required/>
                    <div class="invalid-feedback">
                        Вкажіть шлях до фото.
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-floating">
                    <textarea class="form-control"
                              name="description"
                              placeholder="Leave a comment here"
                              id="description" required
                              style="height: 100px"><?php echo $description ?></textarea>
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
<script>
    $(document).ready(function () {
        $("#input-list").on("change", ".inputImg", function (e) {
            console.log(e.target.files[0]);
            const label = document.querySelector(`label[for="${this.id}"]`);
            const img = label.querySelector('img');
            img.src = URL.createObjectURL(e.target.files[0]);
        });
    });
</script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/bootstrap.validation.js"></script>
</body>
</html>

