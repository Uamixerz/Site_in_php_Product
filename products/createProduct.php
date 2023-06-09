<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Додавання елементу</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <script defer src='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js'></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css'>
</head>

<body>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
<?php
$name = "";
$image = "";
$description = "";
$price = null;
$id_categories = "";
$name_categories = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $uploadDirectory = __DIR__ . '/../dataImages/';
    $saveUrl = '/dataImages/';
    $files = '';
    $price = $_POST['price'];
    $id_categories = $_POST['input-id-categories'];

    if(isset($_FILES["input"])) {


    $files = $_FILES["input"];
    if (!empty($name) && !empty($id_categories) && !empty($description) && !empty($price) && !empty($files)) {
        $stmt = $dbh->prepare("INSERT INTO tbl_products (name, price, description, category_id) VALUES (:name, :price, :description, :id_categories)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id_categories', $id_categories);
        $stmt->execute();

        $newid = $dbh->lastInsertId();

        for($i = 0; $i < count($files["name"]); $i++) {
            $fileName = time() . "_" . rand(1000, 99999) . "_" . $files["name"][$i]; // генеруємо унікальне ім'я файлу

            $fileTmpName = $files["tmp_name"][$i];
            $tempUrlSave = $saveUrl . $fileName;
            $filePath = $uploadDirectory . $fileName;
            //Зберігання на сервер
            move_uploaded_file($fileTmpName, $filePath);
            //Зберігання до бази данних
            $st = $dbh->prepare("INSERT INTO tbl_images (url_image, id_product) VALUES (:img, :id_product)");
            $st->bindParam(':img', $tempUrlSave);
            $st->bindParam(':id_product', $newid);
            $st->execute();
        }

        echo '<script>window.location.replace("' . "/" . '");</script>';
        exit;
    }}
    echo "<h1>упс...</h1>";
}
?>
<main>
    <div class="container">
        <h1 class="text-center">Додати товар</h1>
        <form method="post" class="needs-validation" enctype="multipart/form-data" novalidate >
            <div class="mb-3">
                <label for="name" class="form-label">Назва</label>
                <input type="text" class="form-control" value="<?php echo $name ?>" id="name" name="name" required>
                <div class="invalid-feedback">
                    Вкажіть назву товар.
                </div>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Ціна</label>
                <input type="number" class="form-control" value="<?php echo $price ?>" id="price" name="price" required>
                <div class="invalid-feedback">
                    Вкажіть ціну товара.
                </div>
            </div>

            <div class="mb-3">
                <input type="hidden" name="input-id-categories" value="<?php echo $id_categories ?>"
                       id="input-id-categories" required>
                <label for="input-id-categories" class="form-label">Категорія товару:</label>
                <select class="form-select" id="select-id-categories" required>
                    <option selected disabled value="">Виберіть..</option>
                    <?php
                    $sql = "Select * from tbl_categories";//sql запит
                    $command = $dbh->query($sql);//запускаємо sql запит на нашій бд

                    foreach ($command as $row) {
                        $name = $row["name"];
                        $id_cat = $row["id"];

                        if($id_cat != $id_categories)
                        { echo"<option value='$id_cat'>$name</option>";}
                        else if (!empty($id_categories))
                        {echo"<option value='$id_cat' selected>$name</option>";}
                        else{
                            echo"<option selected >Виберіть...</option>";
                        }
                    }
                    ?>
                </select>
                <div class="invalid-feedback">
                    Вкажіть категорію товара.
                </div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label mb-2">URL фото</label>

                <div id="input-list" class="mb-">
                    <!-- перше поле -->
                    <div class="btn-group mb-2 input-group" data-index="1">
                        <div class="col-sm-10">
                            <label for="input-1">
                                <img src="/dataImages/upload.jpg" style="cursor:pointer;" alt="Фото товару"
                                     width="120px">
                            </label>
                            <input type="file" name="input[]" class="d-none inputImg" id="input-1" value="${file.value}" required/>
                            <div class="invalid-feedback">
                                Вкажіть шлях до фото.
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary add-input ">+</button>
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
                        Вкажіть опис продукту.
                    </div>
                    <label for="description">Опис</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Додати</button>
        </form>

    </div>
</main>
<script>
    const select = document.getElementById('select-id-categories');
    select.addEventListener('change', (event) => {
        const selectedValue = event.target.value;
        document.getElementById('input-id-categories').value = selectedValue;
    });


    $(document).ready(function () {
        // Лічильник для індексування полів input
        var count = 1;

        // Обробник події для кнопки, яка додає нові поля input
        $("#input-list").on("click", ".add-input", function () {
            count++;
            var input =
                '<div class="btn-group mb-2 input-group" data-index="' + count + '">' +
                '<div class="col-sm-10">' +
                '<label for="input-'+count+'">' +
                '<img src="/dataImages/upload.jpg" style="cursor:pointer;" alt="Фото товару" width="120px">'+
                '</label>' +
                '<input type="file" name="input[]" class="d-none inputImg" id="input-'+count+'" required/>' +
                '<div class="invalid-feedback"> Вкажіть шлях до фото. </div>' +
                '</div>' +
                '<button type="button" class="btn btn-primary add-input">+</button>' +
                '<button type="button" class="btn btn-danger remove-input">–</button>' +
                '</div>';

            $("#input-list").append(input);
        });

        $("#input-list").on("change", ".inputImg", function (e){
           console.log(e.target.files[0]);
            const label = document.querySelector(`label[for="${this.id}"]`);
            const img = label.querySelector('img');
            img.src = URL.createObjectURL(e.target.files[0]);
        });
        // видалення
        $("#input-list").on("click", ".remove-input", function () {
            var index = $(this).closest(".input-group").attr("data-index");
            $(this).closest(".input-group").remove();
            // Зміна індексів усіх наступних полів input
            $(".input-group").each(function () {
                if ($(this).attr("data-index") > index) {
                    var newIndex = parseInt($(this).attr("data-index")) - 1;
                    $(this).attr("data-index", newIndex);
                    $(this).find("label").attr("for", "input-" + newIndex);
                    $(this).find("input").attr("id", "input-" + newIndex).attr("name", "input[]");
                }
            });
        });
    });
</script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/bootstrap.validation.js"></script>
<script src="/js/popper.min.js"></script>
</body>
</html>


