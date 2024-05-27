<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}
require_once './back/dbConnection.php';

// Функция для получения данных о заказах из базы данных
function getOrders($statusFilter = null) {
    global $pdo;

    $sql = "SELECT * FROM orders";
    
    // Если установлен фильтр по статусу, добавляем его в запрос
    if (!empty($statusFilter)) {
        $statusFilter = implode("','", $statusFilter);
        $sql .= " WHERE status IN ('$statusFilter')";
    }

    try {
        $stmt = $pdo->query($sql);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}


$statusFilter = isset($_POST['status']) ? $_POST['status'] : null;

$orders = getOrders($statusFilter);
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
                <a href="cart.php">Корзина</a>
                <a href="index.php">Меню</a>
                <button class="header-btn">Заказы</button>
                <a href="/back/sign_up_and_login/logout.php">Выход</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="container-orders">
            <aside class="filter-sidebar">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="filter-form">
                    <label style="font-family: 'Stick', sans-serif;">Фильтр по статусу:</label>
                    <label><input type="checkbox" name="status[]" value="в обработке"> в обработке</label>
                    <label><input type="checkbox" name="status[]" value="ожидает готовки"> ожидает готовки</label>
                    <label><input type="checkbox" name="status[]" value="в готовке"> в готовке</label>
                    <label><input type="checkbox" name="status[]" value="ожидает курьера"> ожидает курьера</label>
                    <label><input type="checkbox" name="status[]" value="переданно курьеру"> переданно курьеру</label>
                    <label><input type="checkbox" name="status[]" value="отмена"> отмена</label>
                    <label><input type="checkbox" name="status[]" value="доставляется"> доставляется</label>
                    <label><input type="checkbox" name="status[]" value="доставлен"> доставлен</label>
                    <label><input type="checkbox" name="status[]" value="возникла ошибка"> возникла ошибка</label>
                    <button type="submit" class="rounded-pill btn btn-primary">Применить фильтр</button>
                </form>
            </aside>
            <div class="main-orders"> <!-- Карточки заказов -->
                <!-- <div class="order-element">
                    <h2>Заказ No<span>1</span></h2>
                    <h2>Статус: 								
                        <form method="post" action="" class="status-form">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <select name="status">
                                <option value="в готовке">в готовке</option>
                                <option value="ожидает курьера">ожидает курьера</option>
                                <option value="отмена">отмена</option>
                                <option value="переданно курьеру">переданно курьеру</option>
                            </select>
                            <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                        </form>
                    </h2>
                    <h2>Курьер: <span>Иванов Иван Иванович</span></h2>
                    <h2>Адрес: <span>Ханты-Мансийск, ул.Чехова, 16</span></h2>
                    <h2>Комментарий:<br>
                        <textarea disabled>
                            Пожалуйста, положите побольше соуса к пасте и добавьте немного острого перца. 
                            Также, если возможно, замените обычный хлеб на безглютеновый. Спасибо
                        </textarea>
                    </h2>
                    <h2>Сумма: <span>1000 руб.</span></h2>
                    <details>
                        <summary>Показать товары</summary>
                        <h4>dfffffffffffffffffddfffffffffffffffffddfffffffffffffffffddfffffffffffffffffddfffffffffffffffffd<?php $items = $order['item_list'];
                            echo substr($items, 0, -2);
                        ?></h4>
                    </details>
                </div> -->
                <?php foreach ($orders as $order): ?>
                    <div class="order-element"> <!-- Карточка заказа -->
                        <h2>Заказ No<span><?php echo $order['order_id']; ?></span></h2>
                        <div class="order-details">
                            <div class="status-section">
                                <h2>Статус:</h2>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                    <select name="status">
                                        <option value="в готовке" <?php if ($order['status'] == 'в готовке') echo 'selected'; ?>>в готовке</option>
                                        <option value="ожидает курьера" <?php if ($order['status'] == 'ожидает курьера') echo 'selected'; ?>>ожидает курьера</option>
                                        <option value="отмена" <?php if ($order['status'] == 'отмена') echo 'selected'; ?>>отмена</option>
                                        <option value="переданно курьеру" <?php if ($order['status'] == 'переданно курьеру') echo 'selected'; ?>>переданно курьеру</option>
                                    </select>
                                    <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                                </form>
                            </div>
                            <div class="info-section">
                                <?php if (!empty($order['courier_id'])): ?>
                                    <h2>Курьер: <span><?php echo $order['courier_id']; ?></span></h2>
                                <?php endif; ?>
                                <h2>Адрес: <span><?php echo $order['address']; ?></span></h2>
                                <h2>Комментарий:</h2>
                                <textarea disabled><?php echo $order['comment']; ?></textarea>
                                <h2>Сумма: <span><?php echo $order['total_price']; ?> руб.</span></h2>
                            </div>
                        </div>
                        <details>
                            <summary>Показать товары</summary>
                            <h4><?php echo $order['item_list']; ?></h4>
                        </details>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <div style="font-family: 'Stick', sans-serif;">
                ©2024 КомуСуси<br>
                <a target="_blank" href="https://github.com/Tsonch/project-web.git">GitHub</a>
            </div>
            <div>
                <a href="#">Корзина</a><br>
                <a href="#">Выход</a>
            </div>
            <div>
                Ханты-Мансийск, ул.Чехова, 16 <br>
                egor.evdakimov@yandex.ru <br>
                +7 902 592 57 30
            </div>
        </div>
    </footer>
</body>

</html>
