<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: main.php');
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
        <form action="/back/sign_up_and_login/signIn.php" method="post" class="login-form">
            <div class="d-flex justify-content-center img-block">
                <img src="./assets/img/logo-susi.png" alt="Логотип" class="img">
            </div>
            <div class="form-body">
                <div class="form-content">
                    <input type="email" placeholder="Email" name="Email" class="form-input text-white">
                </div>
                <div class="form-content">
                    <input type="password" placeholder="Password" name="Password" class="form-input text-white">
                </div>
            </div>
            <button class="form-button" type="submit">Войти</button>
            <div>
                <a href="signUp.php" class="form-href">Зарегистрироваться</a>
            </div>
            <?php
                if (isset($_SESSION['message']) && $_SESSION['message'] == 'Регистрация прошла успешно') {
                    echo '<label class = "h6 text-white"> ' . $_SESSION['message'] . '</label>';
                }
                else if(isset($_SESSION['message'])) {
                    echo '<label class = "h6 text-danger"> ' . $_SESSION['message'] . '</label>';
                }
                unset($_SESSION['message']);
            ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>