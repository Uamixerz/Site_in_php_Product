<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <title>Document</title>
</head>
<body>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/connection.php"); ?>
<?php
$IDcategories = "";
if (isset($_GET['id_categories']))
    $IDcategories = $_GET['id_categories'];
?>

<?php
$userID = "-1";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        $mid = $_POST['id_item_delete'];

        if ($mid != "-1") {
            $sql = "Select * from tbl_images WHERE id_product = $mid";
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
            $stmt->bindParam(':id', $mid);
            $stmt->execute();
            $stmt = $dbh->prepare('DELETE FROM tbl_products WHERE id = :id');
            $stmt->bindParam(':id', $mid);
            $stmt->execute();
        } else if ($_POST['id_item_edit'] != "-1") {
            $mid = $_POST['id_item_edit'];
            $url = "/products/editProduct.php?id_product=" . urlencode($mid);
            echo '<script>window.location.replace("' . $url . '");</script>';
            exit;
        }

    } catch (Exception $ex) {
        print ("Error " . $ex->getMessage() . "<br/>");
    }

}
?>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/_header.php"); ?>
<main>
    <div class="container">
        <form method="post">
        <div class="row align-items-stretch">
            <input type="hidden" name="id_item_delete" value="<?php echo $userID ?>">
            <input type="hidden" name="id_item_edit" value="<?php echo $userID ?>">

            <?php

            if (!empty($IDcategories)) {
                $sql = "Select * from tbl_categories where id = $IDcategories";//sql запит
                $command = $dbh->query($sql);

                foreach ($command as $row) {
                    $name = $row["name"];
                    echo "<h1 class=\"text-center\">$name</h1>";
                }

                $sql = "Select * from tbl_products where category_id = '$IDcategories'";
                $command = $dbh->query($sql);
                $countImage = 0;
                foreach ($command as $row) {
                    $countImage++;
                    $name = $row["name"];
                    $price = $row["price"];
                    $description = $row["description"];
                    $id = $row["id"];
                    $sql = "Select * from tbl_images where id_product = '$id'";
                    $allImg = $dbh->query($sql);
                    $countImg = 0;

                    echo "

            <div class='col-sm mb-3'>
            <div class=\"card\" style=\"width: 18rem; height: 100%\">
            <div id=\"carouselExampleIndicators$countImage\" class=\"carousel carousel-dark slide\" data-bs-ride=\"false\">
            
                <div class=\"carousel-indicators\">
                    <button type=\"button\" data-bs-target=\"#carouselExampleIndicators$countImage\" data-bs-slide-to=\"0\" class=\"active\" aria-current=\"true\" aria-label=\"Slide 1\"></button> 
                    ";
                    foreach ($allImg as $image) {
                        if ($countImg != 0)
                            echo "<button type=\"button\" data-bs-target=\"#carouselExampleIndicators$countImage\" data-bs-slide-to=\"$countImg\" aria-label=\"Slide $countImg\"></button>";
                        $countImg++;
                    }
                    echo "  
                </div>
                
                <div class=\"carousel-inner \">
                ";
                    $allImg = $dbh->query($sql);
                    $countImg = 0;
                    foreach ($allImg as $image) {
                        error_log($image["url_image"]);
                        $url_image = $image["url_image"];

                        if ($countImg != 0) {
                            echo "
                              <div class=\"carousel-item\"  style=\"height: 300px\">
                                <img src=\"$url_image\" class=\"d-block w-100\" alt=\"...\">
                              </div>
                            ";
                        } else {
                            echo "
                            <div class=\"carousel-item active\" style=\"height: 300px\">
                                <img src=\"$url_image\"  class=\"d-block w-100\" alt=\"...\">
                              </div>
                            ";
                            $countImg = 1;
                        }
                    }
                    echo "
                 </div>
                 
                <button class=\"carousel-control-prev\" type=\"button\" data-bs-target=\"#carouselExampleIndicators$countImage\" data-bs-slide=\"prev\">
                    <span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>
                    <span class=\"visually-hidden\">Previous</span>
                </button>
                <button class=\"carousel-control-next\" type=\"button\" data-bs-target=\"#carouselExampleIndicators$countImage\" data-bs-slide=\"next\">
                    <span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>
                    <span class=\"visually-hidden\">Next</span>
                </button>
            </div>
            
                    <div class=\"card-body text-center d-flex flex-column\">
                        <h5 class=\"card-title\">$name</h5>
                        <p class=\"card-text md-5\">$description</p>
                        <div class=\"mt-auto\">
                                <div class=\"text-center\">
                                    <td><button type='submit' class='btn bg-transparent' onclick='editProduct($id)'><i class='fa fa-pencil-square-o fa-2x text-warning'></i></button></td>
                                    <a href=\"#\" class=\"btn btn-primary \" >КУПИТИ $price</a>
                                    <td><button type=\"button\" onclick='updateInputValue($id)' class=\"btn bg-transparent \"><i class='fa fa-trash fa-2x text-danger'></i></button></td>
                                </div>
                        </div>
                    </div>
          </div>
        </div>  
        ";
                }
            }
            $url = "/modals/deleteModal.php";
            include($_SERVER["DOCUMENT_ROOT"] . $url);
            ?>

        </div>
        </form>
    </div>
</main>
<script src="/js/bootstrap.bundle.min.js"></script>
<script src="/js/bootstrap.validation.js"></script>
<script>
    //видалення (Виклик модалкі)
    function updateInputValue(value) {
        var i = new bootstrap.Modal(document.getElementById("deleteModal"));
        i.show();
        document.getElementsByName('id_item_delete')[0].value = value;
    }
    function editProduct(value){
        document.getElementsByName('id_item_delete')[0].value = "-1";
        document.getElementsByName('id_item_edit')[0].value = value;
    }
</script>
</body>
</html>

