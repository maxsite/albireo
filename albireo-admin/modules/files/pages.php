<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Pages
description:
slug: admin/pages
slug-static: -
layout: admin/core/_layout.php
admin-menu[title]: <i class="im-book"></i>Pages
admin-menu[group]: General
admin-menu[order]: 2
parser: -
protect-pre: 0
compress: 0

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$pagesInfo = getVal('pagesInfo');

// отсортируем так, чтобы исключить pages/admin
$pagesInfo = array_filter($pagesInfo, function($val, $key){
    return (strpos($key, ADMIN_DIR) === FALSE);
}, ARRAY_FILTER_USE_BOTH);

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Pages <sup><?= count($pagesInfo) ?></sup></h1>

<?php

$out = '';

$oldDir = '.';

foreach ($pagesInfo as $file => $info) {
    $file1 = str_replace(BASE_DIR, '', $file); // файл указывается относительно BASE_DIR

    $newDir = dirname($file1);

    if ($newDir != $oldDir) {
        $out .= '<h4 class="mar20-t">' . str_replace('\\', '/', $newDir) . ' ↴</h4>';
        $oldDir = $newDir;
    }

    // $title = (isset($info['title']) and $info['title']) ? htmlspecialchars($info['title']) : '<span class="t-red700">! No title</span>';

    $title = (isset($info['title']) and $info['title']) ? htmlspecialchars($info['title']) : '<span class="t-red700">' . $info['slug'] . ' - no title</span>';

    $url = SITE_URL . $info['slug'];
    $editUrl = SITE_URL . 'admin/edit/' . encodeURL64($file1); // файл кодируется в base64

    // $file1 = str_replace('\\', '/', $file1);

    /*
    $file2 = str_replace(DATA_DIR, '<span class="t-red600">Data/</span>', $file);
    $file2 = str_replace(BASE_DIR, '', $file2);
    $file2 = str_replace('\\', '/', $file2);
    */

    if ($method = $info['method'] ?? '') $method = ' / ' . $method;

    $out .= '<div class="mar5-tb pad5-b bor1 bor-gray100 bor-solid-b t90 flex flex-vcenter flex-wrap-phone">'
        . '<div class="w60 w100-phone">'
        . '<a class="im-external-link-alt mar10-r" href="' . $url . '"></a>'
        . '<a class="t-ellipsis" href="' . $editUrl . '" title="Edit file">' . $title . '</a>'
        . '</div>'
        . '<div class="w20 w40-phone t80 mar20-r">' . $info['slug'] . $method . '</div>'
        . '<div class="w20 w40-phone t80 mar20-r t-break-all">' . basename($file) . '</div>'
        . '</div>';
}
?>

<div class="pad30-rl pad10-rl-tablet pad20-b">
    <?= $out ?>
</div>
