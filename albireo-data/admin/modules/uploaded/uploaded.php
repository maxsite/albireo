<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Uploaded
description: 
slug: admin/uploaded
-slug-pattern: admin/uploaded(.*?)
slug-static: -
admin-menu[title]: <i class="im-images"></i>Uploaded
admin-menu[group]: General
admin-menu[order]: 4
layout: admin/core/_layout.php
parser: -
protect-pre: 0
compress: 1
head[]: <script src="[data-url]admin/assets/alpine.min.js"></script>

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$configUpload = getConfigAdmin('upload');
$uploadDir = $configUpload['dir'] ?? 'uploads';

$error = '';

if ($uploadDir and file_exists(BASE_DIR . $uploadDir)) {
    $directory = new \RecursiveDirectoryIterator(BASE_DIR . $uploadDir);
    $iterator = new \RecursiveIteratorIterator($directory);
    // https://www.php.net/manual/ru/class.filesystemiterator.php

    $arrayFiles = [];
    foreach ($iterator as $info) {
        if ($info->isFile()) {
            $d = str_replace(BASE_DIR, '', $info->getPathname());
            $d = str_replace('\\', '/', $d); // windows

            if (basename($d) == '.htaccess') continue; // пропускаем .htaccess
            
            // ключ на основе даты модификации файла чтобы потом отсортировать 
            // будут последние изменения вверху
            $k = $info->getMTime() . ' ' . $d;

            $arrayFiles[$k]['name'] = $d;
            $arrayFiles[$k]['time'] = date('Y-m-d H:i:s', $info->getMTime());
            $arrayFiles[$k]['size'] = human_filesize($info->getSize());
            $arrayFiles[$k]['basename'] = basename($d);
        }
    }

    krsort($arrayFiles);

    if (!$arrayFiles) $error = 'No files in ' . $uploadDir;
} else {
    $error = 'No uploads dir... :-(';
}

$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Uploaded<?= $readOnly ?></h1>

<div class="pad20-rl">
    <?php if (!$error) : ?>
        <?php foreach ($arrayFiles as $file) : ?>
            <div class="flex t90 mar5-b flex-wrap hover-bg-gray100">
                <div class="w20-phone">
                    <!-- <a class="im-external-link-alt icon0 pad5-rl mar5-r" href="<?= SITE_URL . $file['name'] ?>" target="_blank"></a> -->
                    <span x-data="{file: '<?= encodeURL64($file['name']) ?>'}" @click="
                if (confirm('Delete file <?= $file['name'] ?>?')) {
                    document.location.href = '<?= SITE_URL ?>admin/uploaded/delete/' + file;
                }" class="im-times icon0 pad10-rl t-gray200 hover-t-red600 cursor-pointer" title="Delete file"></span>
                </div>

                <div class="flex-grow5 w80-phone pad20-rl t-mono">
                    <a class="t-gray600 hover-t-blue600" href="<?= SITE_URL . $file['name'] ?>" target="_blank"><?= str_replace($file['basename'], '<span class="t-blue600">' . $file['basename'] . '</span>', $file['name']) ?></a>
                </div>
                <div class="t-right t-gray500"><?= $file['time'] ?></div>
                <div class="w100px t-right t-gray500 pad5-r"><?= $file['size'] ?></div>
            </div>
        <?php endforeach ?>
    <?php else : ?>
        <h4><?= $error ?></h4>
    <?php endif ?>
</div>