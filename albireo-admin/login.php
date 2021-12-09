<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Login Form
description: 
slug: admin/login
slug-static: -
layout: admin/core/_landing.php
parser: simple
body: style="min-height: 100vh; background: linear-gradient(to bottom, #4f6778 0%, #E1E7EC 100%)"

 **/

// если есть залогинненость, то форму не выводим
if ($user = getUser()) {
    if (checkUserAccess($user, 'admin'))
        echo '<div class="mar30-tb t-center"><a class="button button1" href="' . SITE_URL . 'admin">Welcome to Admin →</a></div>';

    echo '<div class="mar30-tb t-center"><a class="button button1" href="' . SITE_URL . 'admin/logout">Logout</a></div>';

    return;
} else {
    // возможно уже есть залогиненность, но без разрешения
    if ($user = getUser(true)) {
        echo '<div class="mar30-tb t-center"><a class="button button1" href="' . SITE_URL . 'admin/logout">Logout (' . $user . ')</a></div>';

        return;
    }
}

?>
<div class="h100vh flex flex-vcenter pad100-b">
    <div class="w400px b-center bordered pad30-rl bg-white rounded10 pos-relative b-shadow-var">

        __(pos-absolute t-center t250)(top: -40px; left: 0; right: 0;) <i class="im-user icon0 t-primary600 bg-primary50 icon-circle bor-gray300 bor-solid bor1"></i>

        __(mar60-t t140 t-primary800 mar20-tb t-bold t-center) Albireo <sup class="b-inline t-cyan600 t80 animation-zoom animation-infinite animation-slow">✸</sup><sup class="b-inline t-yellow600 t80 animation-fade animation-infinite animation-slow animation-delay1s">✸</sup>


        <div class="t-red t90"><?php
                                if (isset($errors)) {
                                    echo '<ul><li>' . implode('<li>', $errors) . '</li></ul>';
                                }
                                ?></div>

        <form method="POST">
            <?php
            if (!isset($_SESSION['loginFormReferer']) and isset($_SERVER['HTTP_REFERER'])) {
                $referer = $_SERVER['HTTP_REFERER'];

                if (strpos($referer, SITE_URL) === 0)
                    $_SESSION['loginFormReferer'] = str_replace(SITE_URL, '', $referer);
            }

            if (isset($_SESSION['loginFormReferer'])) {
                if (
                    $_SESSION['loginFormReferer'] == 'admin/login' or
                    $_SESSION['loginFormReferer'] == 'admin/logout'
                )

                    $_SESSION['loginFormReferer'] = 'admin';
            }
            ?>

            div(flex flex-vcenter)
            <i class="im-user1 t-gray400"></i>
            <input class="form-input w100" type="text" name="username" placeholder="username..." required>
            /div

            div(mar20-t flex flex-vcenter)
            <i class="im-hashtag t-gray400"></i>
            <input class="form-input w100" type="password" name="password" placeholder="password..." required>
            /div

            __(mar20-t) <button class="button button1 im-sign-in-alt w100" type="submit">Login</button>
        </form>

        __(mar20-tb t-right) <a href="<?= SITE_URL ?>">Back to Home →</a>

    </div>
</div>