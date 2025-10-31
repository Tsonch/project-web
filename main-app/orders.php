<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once './back/dbConnection.php';

if (isset($_SESSION['user']['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    logAction($pdo, $_SESSION['user']['id'], 'page_visit', 'Page: Cart (' . $_SERVER['REQUEST_URI'] . ')');
}

// Получение роли текущего пользователя
$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];
$query_role = "SELECT role FROM customer WHERE user_id = :user_id";
$stmt_role = $pdo->prepare($query_role);
$stmt_role->execute(['user_id' => $user_id]);
$user_role = $stmt_role->fetchColumn();

// Формирование запроса к базе данных в зависимости от роли пользователя
$fields = '*';

// Получение доступных статусов для текущей роли
$role_status = [
    "Cook" => ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить', 'переданно курьеру', 'отмена'],
    "Courier" => ['ожидает курьера', 'готов доставить', 'переданно курьеру', 'доставляется', 'доставлен', 'возникла ошибка'],
    "Manager" => ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить', 'переданно курьеру', 'доставляется', 'доставлен', 'возникла ошибка', 'отмена']
];

// Обработка формы изменения статуса заказа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $current_status = $_POST['current_status'];
    $courer_id = $_POST['courer_id'] ?? null;
    
    if ($user_role == 'Manager') {
        $new_status = $_POST['status'];
        $query_update_status = "UPDATE orders SET status = :status WHERE order_id = :order_id";
        $stmt_update_status = $pdo->prepare($query_update_status);
        $stmt_update_status->execute(['status' => $new_status, 'order_id' => $order_id]);

        logAction($pdo, $user_id, 'update_order_status', "Order ID: " . $order_id . ", Old: " . $current_status . ", New: " . $new_status . ", Role: " . $user_role);

    } else {
        $action = $_POST['action'];
        if ($action == 'cancel') {
            $new_status = 'отмена';
        } else if ($action == 'advance') {
            $role_statuses = $role_status[$user_role];
            $current_index = array_search($current_status, $role_statuses);
            $new_status = $role_statuses[$current_index + 1] ?? $current_status; // Если статусов больше нет, остается на текущем

            // Проверка для курьера, чтобы он мог изменять статус только своего заказа
            if ($user_role == 'Courier' && $current_status == 'ожидает курьера') {
                $query_update_status = "UPDATE orders SET status = :status, courer_id = :courer_id WHERE order_id = :order_id";
                $stmt_update_status = $pdo->prepare($query_update_status);
                $stmt_update_status->execute(['status' => $new_status, 'courer_id' => $user_id, 'order_id' => $order_id]);

                logAction($pdo, $user_id, 'update_order_status', "Order ID: " . $order_id . ", Old: " . $current_status . ", New: " . $new_status . ", Role: " . $user_role);

            } elseif ($user_role == 'Courier' && $courer_id != $user_id) {
                echo "Вы не можете изменять статус этого заказа.";
                exit();
            } else {
                $query_update_status = "UPDATE orders SET status = :status WHERE order_id = :order_id";
                $stmt_update_status = $pdo->prepare($query_update_status);
                $stmt_update_status->execute(['status' => $new_status, 'order_id' => $order_id]);

                logAction($pdo, $user_id, 'update_order_status', "Order ID: " . $order_id . ", Old: " . $current_status . ", New: " . $new_status . ", Role: " . $user_role);

            }
        }
    }
}

// Запрос к базе данных для получения данных о заказах
$query_orders = "SELECT $fields FROM orders";
$stmt_orders = $pdo->query($query_orders);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
    <title>Суси • Панель заказов</title>
</head>
<body>
    <header>
        <div class="header-container">
            <nav class="header-nav">
                <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                    <a href="index.php">Меню</a>
                <?php } ?>
                <a href="/back/sign_up_and_login/logout.php">Выход</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container-orders">
            <div class="filter-sidebar">
                <form id="filter-form" class="filter-form">
                <label style="font-family: 'Stick', sans-serif;">Фильтр по статусу:</label>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="в обработке"> в обработке</label>
                    <?php } ?>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="ожидает готовки"> ожидает готовки</label>
                    <?php } ?>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="в готовке"> в готовке</label>
                    <?php } ?>
                    <label><input type="checkbox" name="status[]" value="ожидает курьера"> ожидает курьера</label>
                    <label><input type="checkbox" name="status[]" value="переданно курьеру"> переданно курьеру</label>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="отмена"> отмена</label>
                    <?php } ?>
                    <label><input type="checkbox" name="status[]" value="доставляется"> доставляется</label>
                    <label><input type="checkbox" name="status[]" value="доставлен"> доставлен</label>
                    <label><input type="checkbox" name="status[]" value="возникла ошибка"> возникла ошибка</label>
                    <button type="submit">Применить фильтр</button>
                    <button type="button" id="clear-filter">Очистить фильтр</button>
                </form>
            </div>
            <div class="main-orders">
                <?php while ($row = $stmt_orders->fetch(PDO::FETCH_ASSOC)) {
                    // Проверка доступности заказа для текущей роли и статуса
                    if ($user_role == 'Cook' && !in_array($row['status'], ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить'])) {
                        continue;
                    } elseif ($user_role == 'Courier') {
                        // Курьер видит только те заказы, которые назначены на него
                        if (!is_null($row['courer_id']) && $row['courer_id'] != $user_id) {
                            continue;
                        }
                        if (!in_array($row['status'], ['готов доставить', 'ожидает курьера', 'доставляется', 'возникла ошибка', 'переданно курьеру'])) {
                            continue;
                        }
                    } ?>

                    <div class="order-element" <?php echo 'data-status="' . $row['status'] . '"'; ?> >
                        <h2>Заказ No<span><?php echo htmlspecialchars($row['order_id']); ?></span></h2>
                        <h2>Статус: <span><?php echo htmlspecialchars($row['status']); ?></span> </h2>
                        <?php if (!is_null($row['courer_id'])) { ?>
                            <h2>Курьер: <span><?php echo htmlspecialchars($row['courer_id']); ?></span></h2>
                        <?php } ?>
                        <!-- Меню выбора для каждой роли -->
                        <?php if ($user_role == 'Manager') { ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $row['status']; ?>">
                                <select name="status">
                                    <?php foreach ($role_status['Manager'] as $status) { ?>
                                        <option value="<?php echo $status; ?>" <?php echo $row['status'] == $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                    <?php } ?>
                                </select>
                                <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                            </form>

                            <?php if ($row['status'] == 'доставлен' || $row['status'] == 'отмена') { ?>
                                <form method="post" action="back/CRUD/delete_order.php" class="delete-form">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button style="margin-top: 10px;" type="submit" class="rounded-pill btn btn-danger">Удалить заказ</button>
                                </form>
                            <?php } ?>
                            
                        <?php } ?>
                        <?php if ($user_role == 'Cook' || $user_role == 'Courier') { ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $row['status']; ?>">
                                <input type="hidden" name="courer_id" value="<?php echo $row['courer_id']; ?>">
                                <button type="submit" name="action" value="advance" class="rounded-pill btn btn-primary">Продвинуть статус</button>
                                <button type="submit" name="action" value="cancel" class="rounded-pill btn btn-danger">Отменить заказ</button>
                            </form>
                        <?php } ?>
                        <?php if ($user_role != 'Cook') { ?>
                            <h2>Адрес: <span><?php echo htmlspecialchars($row['address']); ?></span></h2>
                        <?php } ?>
                        <h2>Комментарий:<br>
                            <textarea disabled><?php echo htmlspecialchars($row['comment']); ?></textarea>
                        </h2>
                        <h2>Сумма: <span><?php echo htmlspecialchars($row['total_price']); ?> руб.</span></h2>
                        <details>
                            <summary>Состав заказа</summary>
                            <h2>
                                <?php
                                $array = unserialize($row['item_list']);
                                foreach ($array as $key => $item) {
                                    echo $item['name'] . ' — ' . $item['quantity'] . '<br>';
                                }
                                ?>
                            </h2>
                        </details>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('clear-filter').addEventListener('click', function() {
                var checkboxes = document.querySelectorAll('.filter-form input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                document.getElementById('filter-form').submit();
            });
        });
    </script>
</body>
</html>
