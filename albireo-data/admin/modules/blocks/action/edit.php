<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @blocks-save-ajax
slug: admin/blocks/1
slug-pattern: admin/blocks/([0-9]+)
method: AJAX
layout: empty.php
parser: -
compress: 0
protect-pre: 0
init-file: admin/core/_functions.php

 **/

// разрешения для доступа
if (!verifyLogin(['admin'])) exit('Access is denied');
if (!verifyLogin(['admin-change-files'])) exit('Access is denied');

$currentUrl = getVal('currentUrl');
$blockId = (int) str_replace('admin/blocks/', '', $currentUrl['url']);

if ($blockId > 0) {
    $ctrl = new admin\modules\blocks\mvc\Controller;

    if ( isset($_POST['btn']) and $_POST['btn'] == 'delete') {
        $result = $ctrl->delete($blockId);

        // нужно записать flash-сессию которая выводится на странице blocks в виде сообщения
        if ($result !== false) sessionFlashSet('blocks-result', $result);

        echo 'redirect'; // этот текст понимает ajax и по нему делает редирект 

        exit;
    }

    $result = $ctrl->update($blockId, $_POST);
    echo $result;

    // нужно записать flash-сессию
    // которая выводится на странице blocks в виде сообщения
    // if ($result !== false) sessionFlashSet('blocks-result', $result);
    // header('Location:' . SITE_URL . 'admin/blocks');
    // header('Location:' . SITE_URL . 'admin/blocks/' . $blockId); // редирект на эту же страницу
}

# end of file
