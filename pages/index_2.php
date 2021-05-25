<?php

$data_count = 0;

#Подключение к БД
$servername = "localhost";
$username = "root";
$password = "Desaid99";
$dbname = "marvel_heroes";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

#Разбиение строки на массив строк
$viewed_ids = array_filter(explode(',', $_COOKIE['viewed']));
$condition = !empty($viewed_ids) ? 'WHERE id NOT IN (' . $_COOKIE['viewed'] . ')' : '';
#Выбор всех не просмотренных элементов из БД
$sql = 'SELECT * FROM heroes ' . $condition . ' LIMIT 1';
$result = $conn->query($sql);
$hero = $result->fetch_assoc();

#Записывание в Cookie
if (!empty($hero)) {
    $viewed_ids[] = $hero['id'];
    setcookie('viewed', implode(',', $viewed_ids), time() + 3600);
} else {
    setcookie('viewed', '', time() - 3600);
    header('location:' . $_SERVER['REQUEST_URI']);
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marvel heroes</title>
    <link href="../html/css/app.css" type="text/css" rel="stylesheet">
    <link href="../html/js/app.js" type="text/javascript">
</head>
<body>
<div class="heroes">
    <div class="hero <?= $hero['class'] ?>">
        <h3 class="hero-name"><?= $hero['name'] ?></h3>
        <img src="../html/image/<?= $hero['image'] ?>" alt="" class="hero-image"/>
        <p class="hero-description"><?= $hero['short_info'] ?></p>
    </div>
</div>
<div class="update-heroes">
    <a href="" class="button update-heroes-list" title="
            Push me,
            And then just touch me.
            Till I can get my satisfaction">Repeat</a>
    <a href="/" class="button reset-heroes-list">Home</a>
</div>
</body>
</html>