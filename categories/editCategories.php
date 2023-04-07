<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Редагування елементу</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
$urlFile = __DIR__ . '/../dataImages/' . basename($image);
$description = $row["description"];
$id = $row["id"];}
?>
<?php
$uploadDirectory = __DIR__ . '/../dataImages/';
$saveUrl = '/dataImages/';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tempUrlSave = $image;
    $name = $_POST['name'];
    $image = $_FILES['image'];
    $description = $_POST['description'];
    $iName = $_POST['inputIndex'];

    if($iName != 'none')
    {
        if (file_exists($urlFile)) {
            unlink($urlFile);
        }
        $fileName = time() . "_" . rand(1000, 99999) . "_" . $image["name"]; // генеруємо унікальне ім'я файлу
        $fileTmpName = $image["tmp_name"];
        $tempUrlSave = $saveUrl . $fileName;
        $filePath = $uploadDirectory . $fileName;
        //Зберігання на сервер
        move_uploaded_file($fileTmpName, $filePath);
    }
    $stmt = $dbh->prepare("UPDATE tbl_categories SET image = :new_url, name = :name, description = :desc where id = :this_id");
    $stmt->bindParam(':new_url', $tempUrlSave);
    $stmt->bindParam(':this_id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':desc', $description);
    $stmt->execute();

    echo '<script>window.location.replace("' . "/" . '");</script>';
    exit;
}

?>
<main>
    <div class="container">
        <h1 class="text-center">Додати категорію</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label" >Назва</label>
                <input type="text" class="form-control" value="<?php echo $name?>" id="name" name="name">
            </div>
            <div class="mb-3">
                <div id="input-list">
                    <label for="input-1">
                        <input type='hidden' name='inputIndex' class='inputImgIndex' value='none'>
                        <img src="<?php echo $image?>" style="cursor:pointer;" alt="Фото товару"
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
                              id="description"
                              style="height: 100px"><?php echo $description ?></textarea>
                    <label for="description">Опис</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Змінити</button>
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
            const input = document.querySelector(`input.inputImgIndex`);
            if (input.value == 'none')
                input.value = 'edit';
        });
    });
</script>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
