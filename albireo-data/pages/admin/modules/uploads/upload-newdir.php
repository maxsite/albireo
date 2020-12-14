<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @upload-newdir
description: 
slug: admin/upload/newdir
method: POST
slug-static: -
layout: empty.php
parser: -
compress: 0
protect-pre: 0
init-file: pages/admin/core/_functions.php

 **/

if (!verifyLogin(['admin'])) exit('Access is denied');

$dir = $_POST['dir'] ?? false;
if (!$dir) exit('<p class="t-center t-red pad20-tb">ERROR: no dir... :-(</p>');

// находим каталог загрузки из конфигурационного файла
$configUpload = getConfigAdmin('upload');
$uploadDir = $configUpload['dir'] ?? 'uploads';
$uploadDir = BASE_DIR . $uploadDir;

// если нет основного каталога, то создаём
if (verifyLogin(['admin-change-files'])) {
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
}

if (!is_dir($uploadDir)) exit('<div class="t-center t-red pad20-tb">Unable to create a catalog for uploads... :-(</div>');

// подчистка для будущего имени
$dir = strToSlug($dir, false, true);
$dir = trim($dir, '/');

// если каталога нет, создаем 
$fullPath =  $uploadDir . DIRECTORY_SEPARATOR . $dir;

if (verifyLogin(['admin-change-files'])) {
    if (!file_exists($fullPath)) mkdir($fullPath, 0777, true);
}

// и редиректимся назад
header('Location: ' . SITE_URL . 'admin/upload');

# end of file
