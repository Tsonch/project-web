<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'Manager') {
    header("Location: login.php");
    exit();
}

require_once '../dbConnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    $query_delete_order = "DELETE FROM orders WHERE order_id = :order_id";
    $stmt_delete_order = $pdo->prepare($query_delete_order);
    $stmt_delete_order->execute(['order_id' => $order_id]);

    header("Location: ../../orders.php");
    exit();
}
?>
