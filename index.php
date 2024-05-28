<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
} else if ($_SESSION['user']['role'] == "Cook") {
    header("Location: orders.php");
} else if ($_SESSION['user']['role'] == "Courier") {
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

        <form action="/back/CRUD/uploadItem.php" method="post" enctype="multipart/form-data">
        <div class="modal fade" id="managerModal" tabindex="-1" aria-labelledby="managerModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="managerModalLabel">Новый товар</h2>
                </div>
                <div class="modal-body">
                    <form action="/back/CRUD/uploadItem.php" method="post" enctype="multipart/form-data" class="row g-3">
                        <div class="col-6">
                            <label for="inputName" class="form-label h3">Название</label>
                            <input type="text" name='name' class="form-control" id="inputName" required>
                        </div>
                        <div class="col-6">
                            <label for="inputPrice" class="form-label h3">Цена</label>
                            <input type="number" name='price' class="form-control" id="inputPrice" required>
                        </div>
                        <div class="col-6">
                            <label for="inputGrams" class="form-label h3">Граммы</label>
                            <input type="number" name='grams' class="form-control" id="inputGrams" required>
                        </div>
                        <div class="col-6">
                            <label for="inputCategory" class="form-label h3">Категория</label>
                            <input type="text" name='category' class="form-control" id="inputCategory" required>
                        </div>
                        <div class="col-12">
                            <div class="input-group mb-3 h4">
                                <span hidden class="input-group-text h4" id="inputGroupFileAddon01">Изображение</span>
                                <div class="form-file">
                                    <input type="file" name="image" class="form-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="exampleFormControlTextarea1" class="form-label h3">Описание</label>
                            <textarea class="form-control mt-4" name="description" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                </form>
            </div>
        </div>
    </div>
        </form>

        <div class="modal fade" id="managerModalRedact" tabindex="-1" aria-labelledby="managerModalRedactLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="exampleModalLabel">Редактирование</h2>
                    </div>
                    <div class="modal-body">
                        <form action="/back/CRUD/updateItem.php" class="row g-3" method="post" enctype="multipart/form-data">
                            <div class="col-6">
                                <label for="inputAddress" class="form-label h3">Название</label>
                                <input name="name" type="text" class="form-control" id="inputName" required>
                            </div>
                            <div class="col-6">
                                <label for="inputAddress" class="form-label h3">Цена</label>
                                <input name="price" type="number" class="form-control" id="inputPrice" required>
                            </div>
                            <div class="col-6">
                                <label for="inputGrams" class="form-label h3">Граммы</label>
                                <input name="grams" type="number" class="form-control" id="inputGrams" required>
                            </div>
                            <div class="col-6">
                                <label for="inputCategory" class="form-label h3">Категория</label>
                                <input name="category" type="text" class="form-control" id="inputCategory" required>
                            </div>
                            <div class="col-12">
                                <div class="input-group mb-3 h4">
                                    <span hidden class="input-group-text h4" id="inputGroupFileAddon01">Изображение</span>
                                    <div class="form-file">
                                        <input type="file" name="image" class="form-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="exampleFormControlTextarea1" class="form-label h3">Описание</label>
                                <textarea name="description" class="form-control mt-4" id="desc" rows="3"></textarea>
                            </div>
                            <input name="id" id="item_id" type="hidden" value="">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>

                        </form>
                        <div class="modal-footer">
                            <form action="/back/CRUD/deleteItem.php" style="margin-top: 10px" method="post" enctype="multipart/form-data">
                                <input name="id" id="item_id1" type="hidden">
                                <button type="submit" class="btn btn-primary">Удалить</button>
                            </form>
                        </div>


                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade come-from-modal right test-class" tabindex="-1" role="dialog" id="orderModal" aria-labelledby="orderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-style">
                <div class="modal-content">
                    <div class="modal-header modal-background">
                        <h3 class="modal-title text-white" id="orderModalLabel">Заказы</h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body modal-background">
                    <?php
                        $id = $_SESSION['user']['id'];
                        $query = "SELECT * FROM orders Where user_id = $id";
                        $orders = $pdo->query($query);
                        while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <div class="container order">
                                <div class="order-head">
                                    <h3 class="text-black">Заказ №<?php echo $order['order_id'] ?></h3>
                                </div>
                                <div class="order-content">
                                    <p>Адрес: <?php echo $order['address'] ?></p>
                                    <p>Доставить: <?php echo $order['time_duration'] ?></p>
                                    <?php if($order['courer_id'] != null) { ?>
                                        <p>Курьер: <?php echo $order['courer_id'] ?></p>
                                    <?php } ?>
                                    <label for="comment">Комментарий:</label>
                                    <textarea name="comment" class="comm" disabled><?php echo $order['comment'] ?></textarea>
                                    <p>Статус: <?php echo $order['status'] ?></p>
                                    <p>Цена: <?php echo $order['total_price'] ?>р</p>
                                    <details>
                                        <summary>Показать товары</summary>
                                        <h6><?php $items = $order['item_list'];
                                            echo substr($items, 0, -2);
                                        ?></h6>
                                    </details>
                                </div>
                            </div>
                        <?php } ?>
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
                        <button type="button" style="margin-left: 20px;" class="main-btn" data-bs-toggle="modal" data-bs-target="#managerModal">Доб. товар</button>
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
                                <div class="element-buttons">
                                    <form action="/back/CRUD/addToCart.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $tempura_item['item_id'] ?>">
                                        <?php if ($_SESSION['user']['role'] != "Manager") { ?>
                                            <button type="submit" class="main-btn">В корзину</button>
                                        <?php } ?>
                                    </form>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button type="button" onclick="getModal(`<?php echo $tempura_item['name'] ?>`,<?php echo $tempura_item['price'] ?>, `<?php echo $tempura_item['description'] ?>`,<?php echo $tempura_item['item_id'] ?> )" data-bs-toggle="modal" data-bs-target="#managerModalRedact" class="main-btn">Редакт.</button>
                                    <?php } ?>
                                </div>
                                <div>
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
                        <button type="button" style="margin-left: 20px;" class="main-btn" data-bs-toggle="modal" data-bs-target="#managerModal">Доб. товар</button>
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
                                <div class="element-buttons">
                                    <form action="/back/CRUD/addToCart.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $yakitori_item['item_id'] ?>">
                                        <?php if ($_SESSION['user']['role'] != "Manager") { ?>
                                            <button type="submit" class="main-btn">В корзину</button>
                                        <?php } ?>
                                    </form>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button type="button" onclick="getModal(`<?php echo $yakitori_item['name'] ?>`,<?php echo $yakitori_item['price'] ?>, `<?php echo $yakitori_item['description'] ?>`,<?php echo $yakitori_item['item_id'] ?> )" data-bs-toggle="modal" data-bs-target="#managerModalRedact" class="main-btn">Редакт.</button>
                                    <?php } ?>
                                </div>
                                <div>
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
                        <button type="button" style="margin-left: 20px;" class="main-btn" data-bs-toggle="modal" data-bs-target="#managerModal">Доб. товар</button>
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
                                <div class="element-buttons">
                                    <form action="/back/CRUD/addToCart.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?php echo $sukiyaki_item['item_id'] ?>">
                                        <?php if ($_SESSION['user']['role'] != "Manager") { ?>
                                            <button type="submit" class="main-btn">В корзину</button>
                                        <?php } ?>
                                    </form>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button type="button" onclick="getModal(`<?php echo $sukiyaki_item['name'] ?>`,<?php echo $sukiyaki_item['price'] ?>, `<?php echo $sukiyaki_item['description'] ?>`,<?php echo $sukiyaki_item['item_id'] ?> )" data-bs-toggle="modal" data-bs-target="#managerModalRedact" class="main-btn">Редакт.</button>
                                    <?php } ?>
                                    </div>
                                <div>
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
                        <button type="button" style="margin-left: 20px;" class="main-btn" data-bs-toggle="modal" data-bs-target="#managerModal">Доб. товар</button>
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
                                    <?php if ($_SESSION['user']['role'] != "Manager") { ?>
                                        <button class="main-btn">В корзину</button>
                                    <?php } ?>
                                    <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                                        <button type="button" onclick="getModal(`<?php echo $okonomiyaki_item['name'] ?>`,<?php echo $okonomiyaki_item['price'] ?>, `<?php echo $okonomiyaki_item['description'] ?>`,<?php echo $okonomiyaki_item['item_id'] ?> )" data-bs-toggle="modal" data-bs-target="#managerModalRedact" class="main-btn">Редакт.</button>
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

    <script>
        function getModal(name, price, desc, id) {
            document.getElementById("inputName").value = name;
            document.getElementById("inputPrice").value = price;
            document.getElementById("desc").innerText = desc;
            document.getElementById("item_id").value = id;
            document.getElementById("item_id1").value = id;
        }
    </script>
</body>

</html> 