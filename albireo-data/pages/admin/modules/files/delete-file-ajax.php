<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @delete-file-ajax
description: 
slug: admin/delete-file
method: AJAX
slug-static: -
layout: empty.php
parser: -
compress: 0
protect-pre: 0
init-file: pages/admin/core/_functions.php

 **/

// отсекаем всё, что без заголовка AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) exit('Error: AJAX only!');

// нужные данные в POST
$file64 = $_POST['file'] ?? false;

// проверки
if ($file64 === false) exit('ERROR! No file');

$file = decodeURL64($file64);
if (!$file) exit('ERROR! Incorrect file name');

if (!file_exists(DATA_DIR . $file)) exit('ERROR! File not found');

// если есть каталог backup, то переместим его туда
if (is_dir(DATA_DIR . 'backup') and strpos($file, DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR) === false)
    copy(DATA_DIR . $file, DATA_DIR . 'backup' . DIRECTORY_SEPARATOR . basename($file));

// удалить файл
unlink(DATA_DIR . $file);

// удалить его каталог
$dirForDelete = dirname(DATA_DIR . $file); // каталог файла

// если это каталог и он не backup
if (is_dir($dirForDelete) and strpos($dirForDelete,  DIRECTORY_SEPARATOR . 'backup') === false) {
    // если он пустой, то удаляем
    if (!glob($dirForDelete . DIRECTORY_SEPARATOR . '*')) @rmdir($dirForDelete);
}

echo '<span class="t-green700">OK! File deleted</span>';
