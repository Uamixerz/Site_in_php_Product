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
<?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>
<?php
$name = "";
$image = "";
$description = "";
$price = null;
$id_categories = "";
$name_categories = "";

$IDproduct = "";
if (isset($_GET['id_product'])) {
    $IDproduct = $_GET['id_product'];
    $sql = "Select * from tbl_products WHERE id = $IDproduct;";
    $command = $dbh->query($sql);
    foreach ($command as $row){
        $name = $row["name"];
        $price = $row["price"];
        $description = $row["description"];
        $id_categories = $row["category_id"];
    }
    $sql = "Select * from tbl_images WHERE id_product = $IDproduct;";
    $allImg = $dbh->query($sql);
}
else
{
    echo "<h1 class='text-center text-danger'>Щось пішло не так</h1><br/>";
    exit();
}

?>




<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $inputImg = $_POST['input'];
    $indexInput = $_POST['inputIndex'];
    $price = $_POST['price'];
    $id_categories = $_POST['input-id-categories'];
    foreach ($indexInput as $img)
        echo "$img";
    if (!empty($name) && !empty($id_categories) && !empty($description) && !empty($price) && !empty($inputImg)) {
        $stmt = $dbh->prepare("UPDATE tbl_products SET name = :name, price = :price, description = :description, category_id = :id_categories where id = :mid");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':mid', $IDproduct);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id_categories', $id_categories);
        $stmt->execute();

        $sql = "Select * from tbl_images WHERE id_product = $IDproduct;";
        $existImage = $dbh->query($sql);

        foreach ($existImage as $image)
        {
            $index = $image["id"];
            if(in_array($index, $indexInput))
            {
                $count = 0;
                do
                {
                    if($index == $indexInput[$count])
                    {
                        $stmt = $dbh->prepare("UPDATE tbl_images SET url_image = :new_url where id = :this_id");
                        $stmt->bindParam(':new_url', $inputImg[$count]);
                        $stmt->bindParam(':this_id', $index);
                        $stmt->execute();
                        break;
                    }
                    $count++;
                }while ($index != $indexInput[$count]);
            }
            else{
                $stmt = $dbh->prepare('DELETE FROM tbl_images WHERE id = :id');
                $stmt->bindParam(':id', $index);
                $stmt->execute();
            }
        }
        $count = 0;
        foreach ($indexInput as $index){
            if($index == "-1")
            {
                $st = $dbh->prepare("INSERT INTO tbl_images (url_image, id_product) VALUES (:img, :id_product)");
                $st->bindParam(':img', $inputImg[$count]);
                $st->bindParam(':id_product', $IDproduct);
                $st->execute();
            }
            $count++;
        }


        $url = "/products/tableProduct.php?id_categories=" . urlencode($id_categories);
        header("Location: " . $url);
        exit;
    }
}
?>
<main>
    <div class="container">
        <h1 class="text-center">Додати товар</h1>
        <form method="post" class="needs-validation" novalidate>
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
                <input type="hidden" name="input-id-categories" value="<?php echo $id_categories ?>" id="input-id-categories" required>
                <label for="input-id-categories" class="form-label">Категорія товару:</label>
                <select class="form-select"  id="select-id-categories" required>
                    <option selected disabled value="">Виберіть..</option>
                    <?php
                    $sql = "Select * from tbl_categories";//sql запит
                    $command = $dbh->query($sql);//запускаємо sql запит на нашій бд

                    foreach ($command as $row) {
                        $name = $row["name"];
                        $id_cat = $row["id"];

                               if($id_cat != $id_categories)
                               { echo"<option value='$id_cat'>$name</option>";}
                                else
                               {echo"<option value='$id_cat' selected>$name</option>";}

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
                    <?php
                    $count = 1;
                    foreach ($allImg as $img)
                    {
                        $index = $img['id'];
                        $url = $img['url_image'];
                        if($count != 1)
                        {
                            echo"<div class=\"btn-group mb-2 input-group\" data-index=\"$index\">
                                <div class=\"col-sm-10\">
                                    <input type=\"text\" value='$url' name=\"input[]\" class=\"form-control\" id=\"input-$index\" required/>
                                    <input type='hidden' value='$index' name='inputIndex[]'/>
                                    <div class=\"invalid-feedback\">
                                        Вкажіть шлях до фото.
                                    </div>
                                 </div>
                                <button type=\"button\" class=\"btn btn-primary add-input \">+</button>
                                <button type=\"button\" class=\"btn btn-danger remove-input\">–</button>
                            </div>";
                        }
                        else
                        {
                            echo"
                            <div class=\"btn-group mb-2 input-group\" data-index=\"$index\">
                                <div class=\"col-sm-11\">
                                    <input type=\"text\" value='$url' name=\"input[]\" class=\"form-control\" id=\"input-$index\" required/>
                                    <input type='hidden' value='$index' name='inputIndex[]'/>
                                    <div class=\"invalid-feedback\">
                                        Вкажіть шлях до фото.
                                    </div>
                                 </div>
                                <button type=\"button\" class=\"btn btn-primary add-input \">+</button>
                            </div>
                            ";
                        }
                        $count++;
                    }
                    ?>

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

            <button type="submit" class="btn btn-primary">Змінити</button>
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
                '<input required type="text" name="input[]" class="form-control" id="input-' + count + '">' +
                '<input type="hidden" value="-1" name="inputIndex[]"/>'+
                '<div class="invalid-feedback"> Вкажіть шлях до фото. </div>'+
                '</div>' +
                '<button type="button" class="btn btn-primary add-input">+</button>' +

                '<button type="button" class="btn btn-danger remove-input">–</button>' +
                '</div>';

            $("#input-list").append(input);
        });


        // видалення
        $("#input-list").on("click", ".remove-input", function () {
            var index = $(this).closest(".input-group").attr("data-index");
            $(this).closest(".input-group").remove();
        });
    });
</script>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/bootstrap.validation.js"></script>
<script src="/js/popper.min.js"></script>
</body>
</html>



