<?php  
session_start();
require_once '../dbConnection.php';
$user_id = $_SESSION['user']['id'];
$item_id = $_POST['id'];
// echo var_dump($_SESSION); // из-за этого могут возникать проблемы с header, по типу - Warning: Cannot modify header information - headers already sent by (output started at /var/www/html/back/dbConnection.php:20) in /var/www/html/back/sign_up_and_login/signIn.php on line 29
$query = 'INSERT INTO User_Item (User_ID, Item_ID) VALUES (?, ?)';
$prepare = $pdo -> prepare($query);
if($prepare) {
    $prepare->bindValue(1, $user_id);
    $prepare->bindValue(2, $item_id);
    $prepare->execute();
    
    logAction($pdo, $user_id, 'add_to_cart', "Item ID: " . $item_id);

}
else {
    echo "Ошибка при подготовке запроса: " . $pdo -> errorInfo();
}

header("Location: ../../index.php");
?>