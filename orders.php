<?php
    session_start();
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }

    require_once './back/dbConnection.php';

    // Получение роли текущего пользователя
    $user_id = $_SESSION['user']['id'];
	$user_role = $_SESSION['user']['role'];
    $query_role = "SELECT role FROM customer WHERE user_id = :user_id";
    $stmt_role = $pdo->prepare($query_role);
    $stmt_role->execute(['user_id' => $user_id]);
    $user_role = $stmt_role->fetchColumn();

    // Формирование запроса к базе данных в зависимости от роли пользователя
    switch ($user_role) {
        case 'Manager':
            $fields = '*';
            break;
        case 'Cook':
            // $fields = 'order_id, user_id, courer_id, total_price, status';
            $fields = '*';
            break;
        case 'Courier':
            // $fields = 'order_id, user_id, address, comment, total_price, status';
            $fields = '*';
            break;
        default:
            break;
    }

	// Получение доступных статусов для текущей роли
	$statuses = [];
	switch ($user_role) {
		case 'Cook':
			$statuses = ['в готовке', 'ожидает курьера', 'переданно курьеру', 'отмена'];
			break;
		case 'Courier':
			$statuses = ['готов доставить','доставляется', 'доставлен', 'возникла ошибка'];
			break;
		case 'Manager':
			$statuses = ['в обработке', 'ожидает готовки', 'в готовке', 'ожидает курьера', 'переданно курьеру','переданно курьеру', 'отмена', 'доставляется', 'доставлен', 'возникла ошибка'];
			break;
	}

	$role_status = [
		"Cook" => ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить', 'переданно курьеру', 'отмена'],
		"Courier" => ['ожидает курьера', 'готов доставить', 'переданно курьеру', 'доставляется', 'доставлен', 'возникла ошибка']
	];

	// Обработка формы выбора статуса заказа
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$order_id = $_POST['order_id'];
		$new_status = $_POST['status'];
		if ($user_role == 'Courier') {
			$query_update_status = "UPDATE orders SET courer_id = :user_id, status = :status WHERE order_id = :order_id";
			$stmt_update_status = $pdo->prepare($query_update_status);
			$stmt_update_status->execute(['user_id' => $user_id, 'status' => $new_status, 'order_id' => $order_id]);
		}

		if (in_array($new_status, $statuses)) {
			$query_update_status = "UPDATE orders SET status = :status WHERE order_id = :order_id";
			$stmt_update_status = $pdo->prepare($query_update_status);
			$stmt_update_status->execute(['status' => $new_status, 'order_id' => $order_id]);
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
                <a href="cart.php">Корзина</a>
                <a href="index.php">Меню</a>
                <button class="header-btn">Заказы</button>
                <a href="/back/sign_up_and_login/logout.php">Выход</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container-orders">
            <div class="filter-sidebar">
                <form id="filter-form" class="filter-form">
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
                    <button type="submit">Применить фильтр</button>
                    <button type="button" id="clear-filter">Очистить фильтр</button>
                </form>
            </div>
            <div class="main-orders">
                <?php while ($row = $stmt_orders->fetch(PDO::FETCH_ASSOC)) { 
                    // Проверка доступности заказа для текущей роли и статуса
                    if ($user_role == 'Cook' && !in_array($row['status'], ['ожидает готовки', 'в готовке', 'ожидает курьера', 'отмена', 'готов доставить'])) {
                        continue;
                    } elseif ($user_role == 'Courier' && !in_array($row['status'], ['в готовке', 'готов доставить', 'ожидает курьера', 'доставляется', 'возникла ошибка', 'переданно курьеру'])) {
                        continue;
                    } ?>

                    <div class="order-element" <?php echo 'data-status="' . $row['status'] . '"'; ?> >
                        <h2>Заказ No<span><?php echo htmlspecialchars($row['order_id']); ?></span></h2>
                        <h2>Статус: <span><?php echo htmlspecialchars($row['status']); ?></span> </h2>
                        <!-- Меню выбора для каждой роли -->
                        <?php if ($user_role == 'Manager') { ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <select name="status">
                                    <?php foreach ($statuses as $status) { ?>
                                        <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
                                    <?php } ?>
                                </select>
                                <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                            </form>
                        <?php } ?>
                        <?php if ($user_role == 'Cook') { 
                            $current_status = $row['status'];
                            if ($current_status == "ожидает курьера") 
                                $index = array_search($current_status, $role_status['Cook']) + 2;
                            else 
                                $index = array_search($current_status, $role_status['Cook']) + 1; 
                        ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <select name="status">
                                    <option value="<?php echo $role_status["Cook"][$index] ?>"><?php echo $role_status["Cook"][$index] ?></option>
                                    <option value="<?php echo $role_status["Cook"][5] ?>"><?php echo $role_status["Cook"][5] ?></option>
                                </select>
                                <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                            </form>
                        <?php } ?>
                        <?php if ($user_role == 'Courier') { 
                            $current_status = $row['status'];
                            if ($current_status == "готов доставить") 
                                $index = array_search($current_status, $role_status['Courier']) + 2;
                            else 
                                $index = array_search($current_status, $role_status['Courier']) + 1;
                        ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <select name="status">
                                    <option value="<?php echo $role_status["Courier"][$index] ?>"><?php echo $role_status["Courier"][$index] ?></option>
                                    <option value="<?php echo $role_status["Courier"][5] ?>"><?php echo $role_status["Courier"][5] ?></option>
                                </select>
                                <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                            </form>
                        <?php } ?>
                        <?php if (!is_null($row['courer_id'])) { ?>
                            <h2>Курьер: <span><?php echo htmlspecialchars($row['courer_id']); ?></span></h2>
                        <?php } ?>
                        <h2>Адрес: <span><?php echo htmlspecialchars($row['address']); ?></span></h2>
                        <h2>Комментарий:<br>
                            <textarea disabled><?php echo htmlspecialchars($row['comment']); ?></textarea>
                        </h2>
                        <h2>Сумма: <span><?php echo htmlspecialchars($row['total_price']); ?> руб.</span></h2>
                        <details>
                            <summary>Показать товары</summary>
                            <h4><?php echo htmlspecialchars($row['item_list']); ?></h4>
                        </details>
                    </div>
                <?php } ?>
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

<script>
    document.getElementById('filter-form').addEventListener('submit', function(event) {
        event.preventDefault();
        filterOrders();
    });

    document.getElementById('clear-filter').addEventListener('click', function() {
        clearFilter();
    });

    function filterOrders() {
        const checkboxes = document.querySelectorAll('input[name="status[]"]:checked');
        const statuses = Array.from(checkboxes).map(checkbox => checkbox.value);
        const orders = document.querySelectorAll('.order-element');
        
        orders.forEach(order => {
            const orderStatus = order.dataset.status;
            if (statuses.length === 0 || statuses.includes(orderStatus)) {
                order.style.display = 'block';
            } else {
                order.style.display = 'none';
            }
        });
    }

    function clearFilter() {
        const checkboxes = document.querySelectorAll('input[name="status[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = false);
        const orders = document.querySelectorAll('.order-element');
        orders.forEach(order => order.style.display = 'block');
    }
</script>


</html>