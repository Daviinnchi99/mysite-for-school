<?php
include "../vendor/autoload.php";

$errors = [
    'name' => [],
    'description' => [],
    'image' => [],
];
#Если глобальный массив POST существует, то начать получение данных с формы
if (!empty($_POST)) {

    $regexp = '/[^!A-Za-z \-0-9$]/';
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    #Добавление ограничения на минимальную длину имени
    if (empty($name)) {
        $errors['name'][] = 'Name is empty! Fill in the field';
    }
    #Добавление ограничения на максимальную длину имени
    if (strlen($name) > 255) {
        $errors['name'][] = 'Name field can\'t fill more than 255 symbols';
    }
    #Добавление регулярки(фильтра для имени)
    if (preg_match($regexp, $name) === 1) {
        $errors['name'][] = 'Name field can consist only of symbols like A-Z and -';
    }
    #Добавление ограничения на минимальную длину истории героя
    if (empty($description)) {
        $errors['description'][] = 'Description is empty! Fill in the field';
    }

    $servername = "localhost";
    $username = "root";
    $password = "Desaid99";
    $dbname = "marvel_heroes";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT id FROM heroes WHERE name='$name'";
    $result = $conn->query($sql);
    $view = $result->fetch_assoc();
    if (!empty($view)) {
        $errors['name'][] = 'Name have to be unique(This name is already exist)';
    }

    if (empty($image)) {
        $errors['image'][] = 'Image field have to be filled';
    } else {

        #Проверка формата загружаемого файла
        $extension = pathinfo($_FILES['image']['name'])['extension'];
        $available_extensions = ['jpg', 'jpeg', 'png',];
        if (!in_array($extension, $available_extensions)) {
            $errors['image'][] = 'This format is not available, choose image';
        }
    }

    #Если нету ошибок, то начать запись данных в БД
    if (empty(array_filter($errors))) {

        #Сохранение файла в папку image, если нету ошибок в проверке формата
        $uploaddir = 'C:\Program Files\OpenServer\domains\marvel\html\image\\';
        $path_info = pathinfo(basename($_FILES['image']['name']));
        $image_name = md5($path_info['filename']. time()) . '.' . $path_info['extension'];
        $uploadfile = $uploaddir . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
            $image = $image_name;
        } else {
            echo "Возможная атака с помощью файловой загрузки!\n";
        }

        $sql = "INSERT heroes(name, description, image) VALUES ('$name', '$description', '$image');";
        if ($conn->query($sql) === true) {
            header('location:/pages/view.php?id=' . mysqli_insert_id($conn));
        } else {
            dump("Error: " . $sql . "<br>" . $conn->error);
        }
    }
    $conn->close();
}


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
<div class="add-hero-form">
    <form method="post" enctype="multipart/form-data">
        <div class="element-form">
            <input type="text" name="name" class="hero-name-form" value="<?= $name ?>"/>
            <?php foreach ($errors['name'] as $error) { ?>
                <div class="error"><?= $error ?></div>
            <?php } ?>
        </div>
        <div class="element-form">
            <textarea name="description" class="hero-description-form"><?= $description ?></textarea>
            <?php foreach ($errors['description'] as $error) { ?>
                <div class="error"><?= $error ?></div>
            <?php } ?>
        </div>
        <div class="element-form">
            <input type="file" name="image" class="hero-image-form">
            <?php foreach ($errors['image'] as $error) { ?>
                <div class="error"><?= $error ?></div>
            <?php } ?>
        </div>
        <div class="element-form">
            <button type="submit" class="button submit-hero">Submit</button>
            <a href="/" class="button update-heroes-list" title="
            Push me,
            And then just touch me.
            Till I can get my satisfaction">Back</a>
        </div>
    </form>
</div>
</body>
</