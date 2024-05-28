<?php
require_once '../dbConnection.php';

if (!empty($_FILES['image']['name'])) {
    $file = $_FILES['image'];
    $filename = $file['name'];
    $path_info = pathinfo($filename);
    $imagepath = '../../assets/img/' . str_replace(' ', '_', $filename);
    $extension = $path_info['extension'];

    // Проверка допустимых расширений
    if (!in_array($extension, ['png', 'bmp', 'jpg', 'jpeg'])) {
        header("Location: ../../index.php");
        exit;
    }

    // Параметры товара из формы
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $grams = $_POST['grams'];
    $category = $_POST['category'];

    // Перемещение загруженного файла в папку assets/img
    if (move_uploaded_file($file['tmp_name'], $imagepath)) {
        // Относительный путь к изображению для базы данных
        $imagepath_db = 'assets/img/' . str_replace(' ', '_', $filename);

        $query = 'INSERT INTO Items (Name, Img_Path, Price, Description, Grams, Category) VALUES (?, ?, ?, ?, ?, ?)';
        $prepare = $pdo->prepare($query);
        if ($prepare) {
            $prepare->bindValue(1, $name);
            $prepare->bindValue(2, $imagepath_db);
            $prepare->bindValue(3, $price);
            $prepare->bindValue(4, $description);
            $prepare->bindValue(5, $grams);
            $prepare->bindValue(6, $category);
            $prepare->execute();
        } else {
            echo "Ошибка при подготовке запроса: " . $pdo->errorInfo()[2];
        }

        $pdo = null;
        header("Location: ../../index.php");
    } else {
        echo "Ошибка при перемещении файла.";
    }
} else {
    echo "Ошибка загрузки файла.";
}
?>
