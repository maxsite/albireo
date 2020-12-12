<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Pages
description: 
slug: admin/pages
slug-static: -
layout: pages/admin/core/_layout.php
menu[title]: <i class="im-book"></i>Pages
menu[group]: General
menu[order]: 2
parser: -
protect-pre: 0
compress: 0

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$pagesInfo = getVal('pagesInfo');

// отсортируем так, чтобы исключить pages/admin
$pagesInfo = array_filter($pagesInfo, function($val, $key){
    return (strpos($key, DATA_DIR . 'pages' . DIRECTORY_SEPARATOR  . 'admin' . DIRECTORY_SEPARATOR) === FALSE);
}, ARRAY_FILTER_USE_BOTH);

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Pages <sup><?= count($pagesInfo) ?></sup></h1>

<?php

$out = '';

$oldDir = '.';

foreach ($pagesInfo as  $file => $info) {
    $file1 = str_replace(DATA_DIR, '', $file); // файл указывается относительно DATA_DIR
    
    $newDir = dirname($file1);

    if ($newDir != $oldDir) {
        $out .= '<h4 class="mar20-t">' . str_replace('\\', '/', $newDir) . ' ↴</h4>';
        $oldDir = $newDir;
    }

    // $title = (isset($info['title']) and $info['title']) ? htmlspecialchars($info['title']) : '<span class="t-red700">! No title</span>';
    
    $title = (isset($info['title']) and $info['title']) ? htmlspecialchars($info['title']) : '<span class="t-red700">' . $file1 . ' - no title</span>';

    $url = SITE_URL . $info['slug'];
    $editUrl = SITE_URL . 'admin/edit/' . encodeURL64($file1); // файл кодиуется в base64

    $file1 = str_replace('\\', '/', $file1);
    
    $out .= '<div class="mar5-tb pad5-b bor1 bor-gray100 bor-solid-b t90 flex flex-vcenter flex-wrap-phone">'
        . '<div class="w45">'
        . '<a class="im-external-link-alt mar10-r" href="' . $url . '"></a>'
        . '<a class="t-ellipsis" href="' . $editUrl . '" title="Edit file">' . $title . '</a>'
        . '</div>'
        . '<div class="w20 t80 mar20-r">' . $info['slug'] . '</div>'
        . '<div class="w35 t80 mar20-r t-break-all">' . $file1 . '</div>'
        . '</div>';

    

}
?>

<div class="pad30-rl pad10-rl-tablet pad20-b">
    <?= $out ?>
</div>
