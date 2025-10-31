<?php
session_start();

require_once './back/dbConnection.php';
if (isset($_SESSION['user']['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    logAction($pdo, $_SESSION['user']['id'], 'page_visit', 'Page: Sign Up (' . $_SERVER['REQUEST_URI'] . ')');# , $_SERVER['REMOTE_ADDR']);
}

if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel="stylesheet" href="assets/css/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container login-page" style="height: 100vh;">
        <form action="./back/sign_up_and_login/createAcc.php" method="post" class="signUp-form">
            <div class="d-flex justify-content-center img-block">
                <img src="./assets/img/logo-susi.png" alt="Логотип" class="img">
            </div>
            <div class="form-body">
                <div class="form-content">
                    <input type="text" placeholder="First name" name="Name" class="form-input text-white">
                </div>
                <div class="form-content">
                    <input type="text" placeholder="Last name" name="Surname" class="form-input text-white">
                </div>
                <div class="form-content">
                    <input type="email" placeholder="Email" name="Email" class="form-input text-white">
                </div>
                <div class="form-content">
                    <input type="password" placeholder="Password" name="Password" class="form-input text-white">
                </div>
                <div class="form-content">
                    <input type="password" placeholder="Confirm password" name="CPassword" class="form-input text-white">
                </div>
            </div>
            <button class="form-button" type="submit">Зарегистрироваться</button>
            <div>
                <a href="login.php" class="form-href">Войти</a>
            </div>
            <?php
                if (isset($_SESSION['message'])) {
                    echo '<label class = "h6 text-danger"> ' . $_SESSION['message'] . '</label>';
                }
                unset($_SESSION['message']);
            ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>