<?php
require_once '../dbConnection.php';

if (!empty($_POST['id']) && !empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['grams']) && !empty($_POST['category'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $grams = $_POST['grams'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Обновление изображения, если новое загружено
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $filename = $file['name'];
        $path_info = pathinfo($filename);
        $imagepath = '../../assets/img/' . str_replace(' ', '_', $filename);
        $extension = $path_info['extension'];

        if (!in_array($extension, ['png', 'bmp', 'jpg', 'jpeg'])) {
            header("Location: ../../index.php");
            exit;
        }

        if (move_uploaded_file($file['tmp_name'], $imagepath)) {
            // Относительный путь к изображению для базы данных
            $imagepath_db = 'assets/img/' . str_replace(' ', '_', $filename);
            $query = 'UPDATE Items SET Name = ?, Img_Path = ?, Price = ?, Description = ?, Grams = ?, Category = ? WHERE item_id = ?';
            $prepare = $pdo->prepare($query);
            $prepare->bindValue(2, $imagepath_db);
        } else {
            echo "Ошибка при перемещении файла.";
            exit;
        }
    } else {
        $query = 'UPDATE Items SET Name = ?, Price = ?, Description = ?, Grams = ?, Category = ? WHERE item_id = ?';
        $prepare = $pdo->prepare($query);
    }

    if ($prepare) {
        $prepare->bindValue(1, $name);
        $prepare->bindValue(3, $price);
        $prepare->bindValue(4, $description);
        $prepare->bindValue(5, $grams);
        $prepare->bindValue(6, $category);
        $prepare->bindValue(7, $id);
        $prepare->execute();
    } else {
        echo "Ошибка при подготовке запроса: " . $pdo->errorInfo()[2];
    }

    $pdo = null;
    header("Location: ../../index.php");
} else {
    echo "Недостаточно данных для обновления.";
}
?>
