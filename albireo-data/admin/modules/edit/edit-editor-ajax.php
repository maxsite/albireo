<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @edit-ajax
slug: admin/edit
method: AJAX
slug-pattern: admin/edit(.*?)
slug-static: -
layout: empty.php
parser: -
protect-pre: 0
compress: 0
init-file: admin/core/_functions.php

 **/

if (!verifyLogin(['admin'])) exit('Access is denied');

// отсекаем всё, что без заголовка AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) exit('Error: AJAX only!');

// нужные данные в POST
$file64 = $_POST['file'] ?? false;
$content = $_POST['content'] ?? false;

// проверки
if ($file64 === false) exit('ERROR! No file');
if ($content === false) exit('ERROR! No content');

$file = decodeURL64($file64);
if (!$file) exit('ERROR! Incorrect file name');

if (!file_exists(BASE_DIR . $file)) exit('ERROR! File not found');

if (verifyLogin(['admin-change-files'])) file_put_contents(BASE_DIR . $file, $content);

echo '<span class="t-green700">OK! Saved in ' . date('H:i:s Y-m-d') . '</span>';
