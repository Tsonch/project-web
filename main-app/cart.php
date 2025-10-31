<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
} else if ($_SESSION['user']['role'] == "Cook") {
    header("Location: orders.php");
    exit();
} else if ($_SESSION['user']['role'] == "Manager") {
    header("Location: index.php");
    exit();
}
require_once './back/dbConnection.php';

if (isset($_SESSION['user']['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    logAction($pdo, $_SESSION['user']['id'], 'page_visit', 'Page: Cart (' . $_SERVER['REQUEST_URI'] . ')');
}

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

    <title>Суси • Корзина</title>
</head>
<body>
    <header>
        <div class="header-container">
            <nav class="header-nav" style="width: 315px;">
                <a href="index.php">Назад</a>
                <a href="/back/sign_up_and_login/logout.php">Выход</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="main-Cardcontainer">
            <div class="container-item"> <!-- Содержит в себе пункт корзины -->
                <div class="item-title"> <!-- Заголовок пукнта корзины -->
                    <h2>КОРЗИНА</h2>
                </div>
                <?php
                $user_id = $_SESSION['user']['id'];
                $query = "SELECT item_id FROM user_item WHERE User_ID = '$user_id'";
                $cart = $pdo->query($query);

                $items = [];
                $total_price = 0;
                $total_items = "";

                // Сгруппировать заказы и посчитать количество каждого товара
                while ($item = $cart->fetch(PDO::FETCH_ASSOC)) {
                    $item_id = $item['item_id'];
                    if (!isset($items[$item_id])) {
                        $items[$item_id] = ['quantity' => 0];
                    }
                    $items[$item_id]['quantity']++;
                }

                if (count($items) > 0) {
                    foreach ($items as $item_id => $item) {
                        $query = "SELECT * FROM Items WHERE Item_ID = '$item_id'";
                        $itemData = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);

                        $items[$item_id]['name'] = $itemData['name'];
                        $items[$item_id]['price'] = $itemData['price'];
                        $items[$item_id]['grams'] = $itemData['grams'];
                        $items[$item_id]['img_path'] = $itemData['img_path'];

                        $total_price += $itemData['price'] * $item['quantity'];
                        $total_items .= $itemData['name'] . ' x' . $item['quantity'] . ', ';
                    }
                    foreach ($items as $item_id => $item) {
                ?>
                        <div class="item-elements"> <!-- Содержит в себе все товары в корзине -->
                            <div class="element"> <!-- Карточка товара корзины -->
                                <div class="element-img">
                                    <img src="<?php echo htmlspecialchars($item['img_path']); ?>" alt="товар">
                                </div>
                                <div class="element-content">
                                    <h3><?php echo htmlspecialchars($item['name']); ?> <span>x <?php echo $item['quantity']; ?></span></h3>
                                    <div>
                                        <span><?php echo htmlspecialchars($item['price']); ?> ₽</span><span style="margin-left: 20px;"><?php echo htmlspecialchars($item['grams']); ?> гр.</span>
                                    </div>
                                </div>
                                <form action="/back/CRUD/deleteCartItem.php" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user']['id']; ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                                    <button class="cart-DelBtn" type="submit">✖</button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="item-summ"> <!-- Содержит в себе сумму корзины -->
                        <h2>Итого: <span><?php echo $total_price; ?> ₽</span></h2>
                    </div>
                <?php } ?>
            </div>
            <div class="container-item"> <!-- Содержит в себе пункт корзины -->
                <div class="item-title"> <!-- Заголовок пукнта корзины -->
                    <h2>ОФОРМЛЕНИЕ ЗАКАЗА</h2>
                </div>
                <form class="card-form" method="post" enctype="multipart/form-data" action="/back/CRUD/createOrder.php">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                    <input type="hidden" name="items_list" value="<?php echo htmlspecialchars($total_items); ?>">
                    <div class="item-content">
                        <div class="form-place">
                            <div class="content-form">
                                <label for="inputAddress">Улица <textarea class="text-street" name="street" id="inputAddress"></textarea></label>
                            </div>
                            <div class="content-form">
                                <label for="inputAddress">Дом <textarea class="text-house" name="house" id="inputAddress"></textarea></label>
                            </div>
                            <div class="content-form">
                                <label for="inputAddress">Квартира <textarea class="text-flat" name="flat" id="inputAddress"></textarea></label>
                            </div>
                        </div>
                        <div>
                            <select class="form-select h3" name="time_dur" aria-label="Default select example">
                                <option selected>Как можно скорее</option>
                                <option><?php echo date('H:i', time() + (2 * 3600) + 3600); ?></option>
                                <option><?php echo date('H:i', time() + (2 * 3600) + (3600 * 1.5)); ?></option>
                                <option><?php echo date('H:i', time() + (2 * 3600) + (3600 * 2)); ?></option>
                                <option><?php echo date('H:i', time() + (2 * 3600) + (3600 * 2.5)); ?></option>
                            </select>
                        </div>
                        <div class="content-comment">
                            <label for="exampleFormControlTextarea1">Комментарий к заказу <textarea class="text-comment" name="description" id="exampleFormControlTextarea1"></textarea></label>
                        </div>
                    </div>
                    <button style="margin-top: 50px;" class="main-btn" type="submit">Заказать</button>
                </form>
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
                <a href="index.html">Назад</a><br>
                <a href="login.html">Выход</a>
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
