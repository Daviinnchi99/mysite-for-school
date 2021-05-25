<?php
#Получение айди героя по ГЕТу
$hero_id = $_GET['id'];

$servername = "localhost";
$username = "root";
$password = "Desaid99";
$dbname = "marvel_heroes";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
#Выбор героя из бд при помощи полученого айди
$sql = 'SELECT * FROM heroes WHERE id = '. $hero_id;
$result = $conn->query($sql);
$view = $result->fetch_assoc();

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marvel heroes</title>

    <link href="../html/css/app.css" rel="stylesheet" type="text/css">
    <link href="../html/js/app.js" type="text/javascript">
</head>
<body>
<div class="heroes">
    <div class="hero <?= $view['class'] ?>">
        <h3 class="hero-name"><?= $view['name'] ?></h3>
        <img src="../html/image/<?= $view['image'] ?>" alt="" class="hero-image"/>
        <p class="hero-description"><?= $view['description'] ?></p>
    </div>
</div>
<div class="update-heroes">
    <a href="/" class="button update-heroes-list" title="
            Push me,
            And then just touch me.
            Till I can get my satisfaction">Back</a>
    <a href="add_hero.php" class="button add-hero">Add Hero</a>
</div>
</body>
</html>