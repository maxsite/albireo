<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

require_once __DIR__ . '/_functions.php';

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <!-- nosimple -->
    <meta charset="UTF-8">
    <title><?= getPageDataHtml('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= getPageDataHtml('description') ?>">
    <link rel="shortcut icon" href="<?= ADMIN_ASSETS_URL ?>images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="<?= ADMIN_ASSETS_URL ?>css/fonts/mfont.css">
    <link rel="stylesheet" href="<?= ADMIN_ASSETS_URL ?>css/berry.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        h2 {
            margin-top: 40px;
        }
        #myNav::-webkit-scrollbar {
            width: 14px; 
            height: 14px;
            background-color: #185B56;
        }
        
        #myNav::-webkit-scrollbar-thumb {
            background-color: #1F736B;
            border-radius: 14px;
        }

        #myNav::-webkit-scrollbar-thumb:hover {
            background-color: #217b72;
        }
    </style>
    <?= implode('', getKeysPageData('head', '[val]')) ?>
    <!-- /nosimple -->
</head>
<body <?= getPageData('body') ?>>
    <section id="myNav" class="w250px h100vh overflow-auto pos-fixed overscroll-behavior-contain bg-teal850 z-index99 hide-tablet w100-tablet">

        <div class="b-hide show-tablet pad10-tb t90 t-center animation-fade bg-teal800">
            <button class="button button2" onclick="document.getElementById('myNav').classList.toggle('hide-tablet');">✕ Close menu</button>
        </div>

        <header class="t-teal200 bg-teal900 t140 t-center pad5-tb">
            <span class="t-teal150 t120 mar5-r"></span> <a class="t-teal200 t-bold hover-t-teal100" href="https://maxsite.org/albireo">Albireo
                <!-- <span class="b-inline t-cyan600 animation-zoom animation-infinite animation-slow pos-relative" style="font-size: .4rem; top: -15px;">✸</span><span class="b-inline t-yellow600 animation-fade animation-infinite animation-slow animation-delay1s pos-relative" style="font-size: .6rem; top: -10px">✸</span>--></a>
        </header>

        <nav>
            <div class="pad15-rl t90 mar20-b mar10-t t-teal100 links-no-color t-center">
                <a href="<?= SITE_URL ?>" title="">Home</a>
                &bullet; <a href="<?= SITE_URL ?>admin/logout" title="">Exit<i class="mar10-l im-sign-out-alt"></i></a>
            </div>

            <?php
            if (file_exists(ADMIN_DIR . '/config/_menu-before.php')) require ADMIN_DIR . '/config/_menu-before.php';
            ?>

            <div class="pad15-rl">
                <?php require __DIR__ . '/_menu.php'; ?>
            </div>

            <?php
            if (file_exists(ADMIN_DIR . '/config/_menu-after.php')) require ADMIN_DIR . '/config/_menu-after.php';
            ?>
        </nav>

        <!-- <footer class="mar20-t t90 t-teal200 pad20-rl">
            &copy; <a class="t-white hover-t-gray300" href="https://maxsite.org/albireo">Albireo Framework</a>, 2020
        </footer> -->
    </section>

    <section id="myContent" class="w100-phone mar0-tablet" style="margin-left: 250px; max-width: 1100px;">
        <div id="top"></div>
        <div class="b-hide show-tablet t90 animation-fade bg-yellow600 pad10 t-center">
            <button class="button button1" onclick="document.getElementById('myNav').classList.toggle('hide-tablet');">☰ Open menu</button>
        </div>
        <article class="<?= getPageData('layout[article-class]', '') ?>"><?php require getVal('pageFile'); ?></article>
    </section>
    <?= implode('', getKeysPageData('lazy', '[val]')) ?>
</body>
</html>