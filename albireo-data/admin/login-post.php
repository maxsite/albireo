<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Login
description: 
slug: admin/login
method: POST
slug-static: -
layout: admin/core/_landing.php
parser: simple
compress: 0
protect-pre: 0
body: style="min-height: 100vh; background: linear-gradient(to bottom, #577184 0%, #E1E7EC 100%)"

 **/

$errors = [];

// проверим текущую сессию
$password = $_SESSION['password'] ?? '';
$username = $_SESSION['username'] ?? '';

if ($password and $username) {
    // уже есть — выходим, для выхода нужно нажать ссылку logout
    echo '<div class="t-center pad30 b-center w300px bg-white mar50-t">Already username. <a href="' . SITE_URL . 'admin/logout">Logout</a></div>';
    return;
}

$password = $_POST['password'] ?? '';
$username = $_POST['username'] ?? '';

// проверки пароля и логина поблочно
if (!$password) $errors[] = 'No password';
if (!$username) $errors[] = 'No username';

// длина пароля и логина
if (!$errors) {
    if (mb_strlen($password) < 5) $errors[] = 'The password must be longer than 6 characters';
    if (mb_strlen($username) < 2) $errors[] = 'The username must be longer than 3 characters';
}

if (!$errors) {

    // проверим юзера
    if ($user = checkUser($username, $password)) {

        // записываем данные в сессию
        $_SESSION['username'] = $user['username'];
        $_SESSION['password'] = $user['password'];
        $_SESSION['nik'] = $user['nik'];
        $_SESSION['level'] = $user['level'];

        // выводим сообщение на случай задержки редиректа
        //  echo '<div class="mar30-tb t-center"><a href="' . SITE_URL . 'admin">Welcome to Admin →</a></div>';
        
        // данные входа заносим в базу лога
        Logging\Log::info('Login: ' . $user['username'] . ' IP: ' . $_SERVER['REMOTE_ADDR']);

        // делаем редирект
        // он может быть во флэш-сессии 
        if (isset($_SESSION['onLoginRedirect'])) {
            $redirect = $_SESSION['onLoginRedirect'];
            unset($_SESSION['onLoginRedirect']);
            header('Location:' . $redirect);
        } else {
            if (isset($_SESSION['loginFormReferer'])) {
                $redirect = $_SESSION['loginFormReferer'];
                unset($_SESSION['loginFormReferer']);
                header('Location:' . SITE_URL . $redirect);
            } else {
                header('Location:' . SITE_URL);
            }
        }

        // и выходим
        return;
    }

    $errors[] = 'Invalid login/password';
}

if ($errors) {
    // есть ошибки — выводим форму ещё раз
    require __DIR__ . '/login.php';
}
