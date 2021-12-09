<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); ?>
<!DOCTYPE HTML>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= getPageDataHtml('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= getPageDataHtml('description') ?>">
    <link rel="stylesheet" href="<?= getConfig('assetsUrl') ?>css/berry.css">
    <link rel="shortcut icon" href="<?= getConfig('assetsUrl') ?>images/favicon.png" type="image/x-icon">
    <?= implode(getKeysPageData()) ?>
    <?= implode(getKeysPageData('link', '<link rel="[key]" href="[val]">')) ?>
    <?= implode(getKeysPageData('head', '[val]')) ?>
    <?php require getConfig('templateLayoutDir') . 'doc-parts/style.php'; ?>
</head>