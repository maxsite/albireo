<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

require_once __DIR__ . '/_functions.php';

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= getPageDataHtml('title') ?></title>
    <meta name="description" content="<?= getPageDataHtml('description') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="generator" content="Albireo Framework (https://maxsite.org/albireo)">
    <link rel="stylesheet" href="<?= ADMIN_ASSETS_URL ?>css/berry.css">
    <link rel="stylesheet" href="<?= ADMIN_ASSETS_URL ?>css/fonts/mfont.css">
    <link rel="shortcut icon" href="<?= ADMIN_ASSETS_URL ?>images/favicon.png" type="image/x-icon">
    <?= implode(getKeysPageData('head', '[val]')) ?>
</head>
<body <?= getPageData('body') ?>>
    <?php require getVal('pageFile'); ?>
    <?= implode(getKeysPageData('lazy', '[val]')) ?>
</body>
</html>