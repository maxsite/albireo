<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @options-save-ajax
slug: admin/options/save
method: AJAX
slug-static: -
layout: empty.php
parser: -
compress: 0
protect-pre: 0
init-file: admin/core/_functions.php

 **/

// разрешения для доступа
if (!verifyLogin(['admin'])) exit('Access is denied');
if (!verifyLogin(['admin-change-files'])) exit('Access is denied');

// отсекаем всё, что без заголовка AJAX
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) exit('Error: AJAX only!');

// входящие данные POST
$id = $_POST['id'] ?? '';
$key = $_POST['key'] ?? '';
$val = $_POST['val'] ?? '';

$opt = new admin\modules\options\mvc\Controller;
$result = $opt->update($id, $key, $val);

if ($result !== false) echo $result;

# end of file
