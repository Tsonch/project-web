<?php  
session_start();
require_once '../dbConnection.php';

$item_id = $_POST['item_id'];
$user_id = $_POST['user_id'];

try {
    $query = "DELETE FROM User_Item WHERE User_ID = '$user_id' AND Item_ID = '$item_id'";
    $pdo->exec($query);

    logAction($pdo, $user_id, 'remove_from_cart', "Item ID: " . $item_id);
}
catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

header("Location: ../../cart.php");

?>