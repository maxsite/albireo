<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Edit file
description: 
slug: admin/edit
slug-pattern: admin/edit(.*?)
slug-static: -
layout: pages/admin/core/_layout.php
parser: -
protect-pre: 0
compress: 0

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

// если нет второго сегмента, где закодировно имя файла, то выходим

$currentUrl = getVal('currentUrl');
$segmentFile = basename($currentUrl['url']);
if ($segmentFile == 'edit') $segmentFile = ''; // сегмент указывает на эту же страницу


if ($segmentFile) {
    $fileEdit = decodeURL64($segmentFile);

    if ($fileEdit and file_exists(DATA_DIR . $fileEdit)) {
        // есть такой файл
        require __DIR__ . '/_edit-editor.php'; // редактор файла
        
        return; // выходим
    }
}

echo '<br><div class="pad10 bg-red100 t-red600 bor-red bor1 bor-solid mar30 rounded10"><i class="im-exclamation-triangle"></i>ERROR! File not found</div>';
