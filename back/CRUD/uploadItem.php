<?php
require_once '../dbConnection.php';
if (!empty($_FILES['image']['name'])) {
    $file = $_FILES['image'];
    $filename = $file['name'];
    $path_info = pathinfo($filename);
    $imagepath = 'assets/img/' . $filename; 
    $imagepath = str_replace(" " , "_" ,$imagepath);
    $extention = $path_info['extension'];
    if ($extention !== "png" and $extention !== "bmp" and $extention !== "jpg" and $extention !== "jpeg") {
        header("Location: ../../index.php");
        exit;
    }
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $grams = $_POST['grams'];
    $category = $_POST['category'];

    move_uploaded_file($file['tmp_name'], $imagepath);
   
    $query = 'INSERT INTO Items (Name, Img_Path, Price, Description, Grams, Category) VALUES (?, ?, ?, ?, ?, ?)';
    $prepare = $pdo -> prepare($query);
    if($prepare) {
        $prepare->bindValue(1, $name);
        $prepare->bindValue(2, $imagepath);
        $prepare->bindValue(3, $price);
        $prepare->bindValue(4, $description);
        $prepare->bindValue(5, $grams);
        $prepare->bindValue(6, $category);
        $prepare->execute();
    }
    else {
        echo "Ошибка при подготовке запроса: " . $pdo -> errorInfo();
    }
   
    $pdo = null;
    header("Location: ../../index.php");
}
else {
    echo "Ошибка загрузки файла";
}
?>