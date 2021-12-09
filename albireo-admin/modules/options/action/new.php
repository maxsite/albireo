<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
title: @options-new-post
slug: admin/options
method: POST
slug-static: -
layout: empty.php
parser: -
protect-pre: 0
compress: 0
init-file: admin/core/_functions.php

 **/

// Add new options

if (!verifyLogin(['admin'])) exit('Access is denied');
$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';

$opt = new admin\modules\options\mvc\Controller;
$result = $opt->addNew();

// нужно записать flash-сессию
// которая выводится на странице options в виде сообщения
if ($result !== false) sessionFlashSet('options-result', $result);

header('Location:' . SITE_URL . 'admin/options');
