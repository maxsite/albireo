<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Logout
description: 
slug: admin/logout
slug-static: -
layout: pages/admin/core/_landing.php
parser: -

 **/

// удалим данные из сессии
if (isset($_SESSION['password'])) unset($_SESSION['password']);
if (isset($_SESSION['username'])) unset($_SESSION['username']);
if (isset($_SESSION['nik'])) unset($_SESSION['nik']);
if (isset($_SESSION['loginFormReferer'])) unset($_SESSION['loginFormReferer']);
if (isset($_SESSION['onLoginRedirect'])) unset($_SESSION['onLoginRedirect']);

if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];

    if (strpos($referer, SITE_URL) === 0) {
        if (strpos($referer, SITE_URL . 'admin') === false)
            header('Location:' . $referer);
    }
}

?>

<div class="b-flex flex-jc-center mar100-t">
    <div class="b-flex flex-vcenter">
        <div class="t200 t-white t-bold pad30 bg-green600"><i class="im-info-circle icon0"></i></div>
        <div class="pad20">
            <div class="t200 t-gray600">You logout</div>
            <div class="flex">
                <a class="im-home" href="<?= SITE_URL ?>">Home →</a>
                <!-- <a class="im-sign-out-alt t-gray600" href="<?= SITE_URL ?>admin/login">Login</a> -->
            </div>
        </div>
    </div>
</div>