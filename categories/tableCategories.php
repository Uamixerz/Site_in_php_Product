<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <title>Document</title>
</head>
<body>
<main>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
    <?php
    $userID = "-1";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {

            $mid = $_POST['user_id'];

            if ($mid != "-1") {

                $sql = "Select * from tbl_categories WHERE id = $mid";
                $temp = $dbh->query($sql);
                foreach ($temp as $tempImg)
                {
                $urlFile = $tempImg["image"];
                $urlFile = __DIR__ . '/../dataImages/' . basename($urlFile);
                if (file_exists($urlFile)) {
                    unlink($urlFile);
                }
                $sql = "Select * from tbl_products where category_id = '$mid'";
                $command = $dbh->query($sql);
                foreach ($command as $prod){
                    $id_prod = $prod['id'];
                    $sql = "Select * from tbl_images WHERE id_product = $id_prod";
                    $tempImg = $dbh->query($sql);
                    foreach ($tempImg as $thisImg) {
                        $idImg = $thisImg["id"];
                        $urlFile = $thisImg["url_image"];
                        $urlFile = __DIR__ . '/../dataImages/' . basename($urlFile);
                        if (file_exists($urlFile)) {
                            unlink($urlFile);
                        }
                    }
                    $stmt = $dbh->prepare('DELETE FROM tbl_images WHERE id_product = :id');
                    $stmt->bindParam(':id', $id_prod);
                    $stmt->execute();
                    $stmt = $dbh->prepare('DELETE FROM tbl_products WHERE id = :id');
                    $stmt->bindParam(':id', $id_prod);
                    $stmt->execute();
                }

                $stmt = $dbh->prepare('DELETE FROM tbl_categories WHERE id = :id');
                $stmt->bindParam(':id', $mid);
                $stmt->execute();}
            } else if ($_POST['user_id_edit'] != "-1") {
                $mid = $_POST['user_id_edit'];
                $url = "/categories/editCategories.php?element=" . urlencode($mid);
                echo '<script>window.location.replace("' . $url . '");</script>';
                exit;
            } else {
                $mid = $_POST['user_id_info'];
                $url = "/products/tableProduct.php?id_categories=" . urlencode($mid);
                echo '<script>window.location.replace("' . $url . '");</script>';
                exit;
            }

        } catch (Exception $ex) {
            print ("Error " . $ex->getMessage() . "<br/>");
        }

    }
    ?>
    <div class="container">
        <h1 class="text-center">Список категорій</h1>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Фото</th>
                <th scope="col">Назва</th>
                <th scope="col">Опис</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <form method="post">
                <input type="hidden" name="user_id" value="<?php echo $userID ?>">
                <input type="hidden" name="user_id_edit" value="<?php echo $userID ?>">
                <input type="hidden" name="user_id_info" value="<?php echo $userID ?>">
                <!--Беремо дані з бд-->
                <?php
                $sql = "Select * from tbl_categories";//sql запит
                $command = $dbh->query($sql);//запускаємо sql запит на нашій бд

                foreach ($command as $row) {//Перебираємо кожен елемент
                    $name = $row["name"];//в лапках назва стовбця
                    $image = $row["image"];
                    $description = $row["description"];
                    $id = $row["id"];
                    echo "
                <tr>
                
                    <td><img src='$image' width='50'/></td>
                    <td>$name</td>
                    <td>$description</td>
                    
                    <td><button type='submit' class='btn bg-transparent' onclick='startEdit($id)'><i class='fa fa-pencil-square-o fa-2x text-warning'></i></button></td>
                    <td><button type=\"button\" onclick='updateInputValue($id)' class=\"btn bg-transparent \"><i class='fa fa-trash fa-2x text-danger'></i></button></td>
                    <td><button type='submit' class='btn bg-transparent' onclick='startInfo($id)'><i class='fa fa-arrow-right fa-2x text-info'></i></button></td>
                </tr>
                ";
                }

                ?>
                <?php include($_SERVER["DOCUMENT_ROOT"] . "/modals/deleteModal.php"); ?>
            </form>
            </tbody>
        </table>
    </div>

</main>
<script>
    function updateInputValue(value) {
        document.getElementsByName('user_id')[0].value = value;
        var i = new bootstrap.Modal(document.getElementById("deleteModal"));
        i.show();
    }

    function startEdit(value) {
        document.getElementsByName('user_id')[0].value = "-1";
        document.getElementsByName('user_id_edit')[0].value = value;
    }

    function startInfo(value) {
        document.getElementsByName('user_id')[0].value = "-1";
        document.getElementsByName('user_id_edit')[0].value = "-1";
        document.getElementsByName('user_id_info')[0].value = value;
    }

</script>
</body>
</html>
