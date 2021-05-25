<?php

#Коннект к БД
$servername = "localhost";
$username = "root";
$password = "Desaid99";
$dbname = "marvel_heroes";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

#Выбор 2 случайных героев из БД, если нажали кнопку, иначе выбор всех героев из БД
if (isset($_GET['rand'])) {
    $sql = "SELECT * FROM heroes ORDER BY rand() LIMIT 2";
    $result = $conn->query($sql);
} else {
    $sql = "SELECT * FROM heroes";
    $result = $conn->query($sql);
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marvel heroes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          type="text/css">
<!--    <link href="html/css/app.css" type="text/css" rel="stylesheet">-->
</head>
<body>
<div class="container">
    <div class="col">
        <?php while ($hero = $result->fetch_array()) { ?>
            <!--        <div class="hero --><? //= $hero['class'] ?><!--">-->
            <!--            <h3 class="hero-name">--><? //= $hero['name'] ?><!--</h3>-->
            <!--            <img src="html/image/--><? //= $hero['image'] ?><!--" alt="" class="hero-image"/>-->
            <!--            <p class="hero-short-info">--><? //= $hero['short_info'] ?><!--</p>-->
            <!--            <p><a href="/pages/view.php?id=--><? //= $hero['id'] ?><!--">More info</a></p>-->
            <!--        </div>-->
            <div class="col-md-4">
                <div class="card">
                    <h5 class="card-title"><?= $hero['name'] ?></h5>
                    <img src="html/image/<?= $hero['image'] ?>" class="card-img-top" alt="">
                    <div class="card-body">
                        <p class="card-text"><?= $hero['short_info'] ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<div class="update-heroes">
    <a href="?rand=1" class="button update-heroes-list" title="
            Push me,
            And then just touch me.
            Till I can get my satisfaction">Update</a>
    <a href="/" class="button reset-heroes-list">Reset</a>
    <a href="/pages/index_2.php" class="button random-hero">Random Hero</a>
    <a href="pages/add_hero.php" class="button add-hero">Add Hero</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="html/js/app.js"></script>
</body>
</html>
