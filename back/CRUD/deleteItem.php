<?php 
require_once('../dbConnection.php');
$item_id = $_POST['id'];

try {
    $query = "DELETE FROM User_Item WHERE Item_ID = '$item_id'";
    $pdo->exec($query);
    $query = "DELETE FROM Items WHERE Item_ID ='$item_id'";
    $pdo->exec($query);
}
catch (PDOException $e) {
    die($e->getMessage());
}

header("Location: ../../index.php");
?>