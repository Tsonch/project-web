<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
} else if ($_SESSION['user']['role'] == "Cook") {
    header("Location: orders.php");
}
require_once './back/dbConnection.php';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Суси • Главная</title>
</head>

<body>

    <header>
        <div class="header-container">
            <nav class="header-nav">
                <?php if ($_SESSION['user']['role'] != "Manager") { ?>
                    <a href="cart.php">Корзина</a>
                <?php }
                if ($_SESSION['user']['role'] == "Manager") { ?>
                    <a href="orders.php">Панель заказов</a>
                <?php }
                if ($_SESSION['user']['role'] != "Manager") { ?>
                    <button class="header-btn" data-bs-toggle="modal" data-bs-target="#orderModal">Заказы</button>
                <?php } ?>
                <a href="/back/sign_up_and_login/logout.php">Выход</a>
            </nav>
        </div>
    </header>

    <main>
        <div class="modal fade come-from-modal right" tabindex="-1" role="dialog" id="orderModal" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-style">
                <div class="modal-content">
                    <div class="modal-header modal-background">
                        <h3 class="modal-title text-white" id="orderModalLabel">Заказы</h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body modal-background">
                        <div class="container order">
                            <div class="order-head">
                                <h3 class="text-black">Заказ №10</h3>
                            </div>
                            <div class="order-content">
                                <p>Комментарий: фвофлывтлфывлофылвлфтывлофылвотфлы</p>
                                <p>Статус: доставляется</p>
                                <p>Цена: 3000р</p>
                            </div>
                        </div>
                        <div class="container order">
                            <div class="order-head">
                                <h3 class="text-black">Заказ №10</h3>
                            </div>
                            <div class="order-content">
                                <label for="comment">Комментарий:</label>
                                <textarea name="comment" class="comm" disabled>хуй</textarea>
                                <p>Статус: доставляется</p>
                                <p>Цена: 3000р</p>
                            </div>
                        </div>
                        <div class="container order">
                            <div class="order-head">
                                <h3 class="text-black">Заказ №10</h3>
                            </div>
                            <div class="order-content">
                                <p>Комментарий: фвофлывтлфывлофылвлфтывлофылвотфлы</p>
                                <p>Статус: доставляется</p>
                                <p>Цена: 3000р</p>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer modal-background">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                 </div> -->
                </div>
            </div>
        </div>

        <div class="main-container">

            <div class="container-item"> <!-- Содержит в себе пункт меню -->
                <div class="item-title"> <!-- Заголовок меню -->
                    <h2>ТЕМПУРА</h2>
                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                        <button style="margin-left: 20px;" class="main-btn">Доб. товар</button>
                    <?php } ?>
                </div>
                <?php
                $query = "SELECT * FROM items Where category = 'ТЕМПУРА'";
                $tempura_items = $pdo->query($query);
                ?>
                <div class="item-elements"> <!-- Содержит в себе все карточки с меню -->
                    <?php
                    while ($tempura_item = $tempura_items->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="element"> <!-- Карточка меню -->
                            <div class="element-img">
                                <img src="<?php echo $tempura_item['img_path'] ?>" alt="ТЕМПУРА">
                                <div>
                                    <span><?php echo $tempura_item['price'] ?> ₽</span><span><?php echo $tempura_item['grams'] ?> гр.</span>
                                </div>
                            </div>
                            <div class="element-content">
                                <h3><?php echo $tempura_item['name'] ?></h3>
                                <div>
                                    <?php echo $tempura_item['description'] ?>
                                </div>
                                <div>
                                    <button class="main-btn">В корзину</button>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button class="main-btn">Редакт.</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <div class="container-item"> <!-- Содержит в себе пункт меню -->
                <div class="item-title"> <!-- Заголовок меню -->
                    <h2>ЯКИТОРИ</h2>
                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                        <button style="margin-left: 20px;" class="main-btn">Доб. товар</button>
                    <?php } ?>
                </div>
                <?php
                $query = "SELECT * FROM items Where category = 'ЯКИТОРИ'";
                $yakitori_items = $pdo->query($query);
                ?>
                <div class="item-elements"> <!-- Содержит в себе все карточки с меню -->
                    <?php
                    while ($yakitori_item = $yakitori_items->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="element"> <!-- Карточка меню -->
                            <div class="element-img">
                                <img src="<?php echo $yakitori_item['img_path'] ?>" alt="ТЕМПУРА">
                                <div>
                                    <span><?php echo $yakitori_item['price'] ?> ₽</span><span><?php echo $yakitori_item['price'] ?> гр.</span>
                                </div>
                            </div>
                            <div class="element-content">
                                <h3><?php echo $yakitori_item['name'] ?></h3>
                                <div>
                                    <?php echo $yakitori_item['description'] ?>
                                </div>
                                <div>
                                    <button class="main-btn">В корзину</button>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button class="main-btn">Редакт.</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <div class="container-item"> <!-- Содержит в себе пункт меню -->
                <div class="item-title"> <!-- Заголовок меню -->
                    <h2>СУКИЯКИ</h2>
                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                        <button style="margin-left: 20px;" class="main-btn">Доб. товар</button>
                    <?php } ?>
                </div>
                <?php
                $query = "SELECT * FROM items Where category = 'СУКИЯКИ'";
                $sukiyaki_items = $pdo->query($query);
                ?>
                <div class="item-elements"> <!-- Содержит в себе все карточки с меню -->
                    <?php
                    while ($sukiyaki_item = $sukiyaki_items->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="element"> <!-- Карточка меню -->
                            <div class="element-img">
                                <img src="<?php echo $sukiyaki_item['img_path'] ?>" alt="ТЕМПУРА">
                                <div>
                                    <span><?php echo $sukiyaki_item['price'] ?> ₽</span><span><?php echo $sukiyaki_item['grams'] ?> гр.</span>
                                </div>
                            </div>
                            <div class="element-content">
                                <h3><?php echo $sukiyaki_item['name'] ?></h3>
                                <div>
                                    <?php echo $sukiyaki_item['description'] ?>
                                </div>
                                <div>
                                    <button class="main-btn">В корзину</button>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button class="main-btn">Редакт.</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>


            <div class="container-item"> <!-- Содержит в себе пункт меню -->
                <div class="item-title"> <!-- Заголовок меню -->
                    <h2>ОКОНОМИЯКИ</h2>
                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                        <button style="margin-left: 20px;" class="main-btn">Доб. товар</button>
                    <?php } ?>
                </div>
                <?php
                $query = "SELECT * FROM items Where category = 'ОКОНОМИЯКИ'";
                $okonomiyaki_items = $pdo->query($query);
                ?>
                <div class="item-elements"> <!-- Содержит в себе все карточки с меню -->
                    <?php
                    while ($okonomiyaki_item = $okonomiyaki_items->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="element"> <!-- Карточка меню -->
                            <div class="element-img">
                                <img src="<?php echo $okonomiyaki_item['img_path'] ?>" alt="ТЕМПУРА">
                                <div>
                                    <span><?php echo $okonomiyaki_item['price'] ?> ₽</span><span><?php echo $okonomiyaki_item['grams'] ?> гр.</span>
                                </div>
                            </div>
                            <div class="element-content">
                                <h3><?php echo $okonomiyaki_item['name'] ?></h3>
                                <div>
                                    <?php echo $okonomiyaki_item['description'] ?>
                                </div>
                                <div>
                                    <button class="main-btn">В корзину</button>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button class="main-btn">Редакт.</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>