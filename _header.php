<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Головна сторінка</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php
ob_start();
?>
<!--Меню зверху (хедер) підключаємо його на кожну сторінку-->
<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">MyPhp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Головна</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories/createCategories.php">Добавити категорію</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products/createProduct.php">Добавити продукт</a>
                    </li>
                </ul>
<!--                <form class="d-flex" role="search">-->
<!--                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">-->
<!--                    <button class="btn btn-outline-success" type="submit">Search</button>-->
<!--                </form>-->
            </div>
        </div>
    </nav>
</header>
<?php
ob_end_flush();
?>
<script src="/js/bootstrap.bundle.min.js"></script>
</body>
</html>

