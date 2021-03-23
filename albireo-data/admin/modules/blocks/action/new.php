<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
title: @blocks-new-post
slug: admin/blocks
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

$ctrl = new admin\modules\blocks\mvc\Controller;
$result = $ctrl->addNew();

// нужно записать flash-сессию
// которая выводится на странице blocks в виде сообщения
if ($result !== false) sessionFlashSet('blocks-result', $result);

header('Location:' . SITE_URL . 'admin/blocks');
