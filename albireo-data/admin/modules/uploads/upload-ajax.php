<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @upload-ajax
description: 
slug: admin/upload/ajax
method: POST
slug-static: -
layout: empty.php
parser: -
compress: 0
protect-pre: 0
init-file: admin/core/_functions.php

 **/

if (!verifyLogin(['admin'])) exit('Access is denied');

if (isset($_SERVER['HTTP_X_REQUESTED_FILENAME']))
    $_fn = $_SERVER['HTTP_X_REQUESTED_FILENAME'];
else
    exit('no file');

if (isset($_SERVER['HTTP_X_REQUESTED_FILEUPDIR']))
    $_dr = $_SERVER['HTTP_X_REQUESTED_FILEUPDIR'];
else
    exit('no updir');

// признак, что файлы можно заменять
$replace = $_SERVER['HTTP_X_REQUESTED_REPLACEFILE'] ?? 'false';

$fn = strToSlug($_fn); // файл
$up_dir = BASE_DIR . $_dr; // каталог куда грузим файл

// если нет каталога, то создаём
if (verifyLogin(['admin-change-files'])) {
    if (!file_exists($up_dir)) mkdir($up_dir, 0777, true);
}

if (!is_dir($up_dir)) exit('<div class="t-center t-red pad20-tb">Unable to create a catalog for uploads... :-(</div>');

// Если файл уже существует и нельзя заменять ищем новое имя
if (strtolower($replace) == 'false' and file_exists($up_dir . DIRECTORY_SEPARATOR . $fn)) {
    $ext = strtolower(substr(strrchr($fn, '.'), 1)); // расширение
    $name = substr($fn, 0, strlen($fn) - strlen($ext) - 1); // имя

    // ищем в первых 100
    for ($i = 1; $i < 100; $i++) {
        $fn = $name . '-' . $i . '.' . $ext;

        if (!file_exists($up_dir . DIRECTORY_SEPARATOR . $fn)) break;
    }
}

$out_file = $up_dir . DIRECTORY_SEPARATOR . $fn; // выходной файл

// file_put_contents(BASE_DIR . '/_log.txt', $out_file); // лог для отладки 
// echo '<div class="">' . $out_file . '</div>'; // отладка в браузер

// загрузка
$data = file_get_contents('php://input');

if (verifyLogin(['admin-change-files'])) {
    if ($data) file_put_contents($out_file, $data); // запись файла
}

# end of file
