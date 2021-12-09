<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Upload files
description: 
slug: admin/upload
slug-pattern: admin/upload(.*?)
slug-static: -
admin-menu[title]: <i class="im-upload"></i>Upload files
admin-menu[group]: General
admin-menu[order]: 5
layout: admin/core/_layout.php
parser: -
protect-pre: 0
compress: 1

**/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$configUpload = getConfigAdmin('upload');

if ($configUpload) {
    $uploadDir = $configUpload['dir'] ?? 'uploads';
    $uploadMaxSize = $configUpload['maxSize'] ?? '20000000';
    $uploadExt = $configUpload['ext'] ?? 'mp3|mp4|gif|jpg|jpeg|png|svg|zip|txt|rar|doc|rtf|pdf|html|htm|css|xml|odt|avi|wmv|wav|xls|7z|gz|bz2|tgz';
    $uploadProtectName = $configUpload['protectName'] ?? true;

    $uploadAction = SITE_URL . 'admin/upload/ajax';

    // если нет каталога, то создаём
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    if (!is_dir($uploadDir)) exit('<div class="t-center t-red pad20-tb">Unable to create a catalog for uploads... :-(</div>');

    // получаем все подкаталоги
    // вначале получаем всё, что есть
    // потом убираем дубли
    // и формируем <option>
    $directory = new \RecursiveDirectoryIterator(BASE_DIR . $uploadDir);
    $iterator = new \RecursiveIteratorIterator($directory);

    $arrayDir = [];
    foreach ($iterator as $info) {
        if ($info->isDir()) {
            $d = str_replace(BASE_DIR, '', $info->getPath());
            $d = str_replace('\\', '/', $d); // windows
            $arrayDir[] = $d;
        }
    }

    $arrayDir = array_unique($arrayDir);
    $listDir = '';

    foreach ($arrayDir as $d) {
        $listDir .= '<option value="' . $d . '">' . $d . '</option>';
    }
} else {
    exit('Admin config not found... :-(');
}

$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Upload files<?= $readOnly ?></h1>

<div class="pad20-rl">
    <div class=" mar20-b b-flex flex-vcenter flex-wrap">
        <div class="pad30-r mar10-tb">
            <span>Download to </span>

            <select class="form-input" id="upload_dir" name="upload_dir">
                <?= $listDir ?>
            </select>
        </div>

        <div>
            <label class="form-checkbox t-nowrap">
                <input type="checkbox" id="upload_replace_file" name="upload_replace_file" checked>
                <span class="form-checkbox-icon mar5-r"></span> Replace file
            </label>
        </div>

        <div class="flex-grow5"></div>

        <div class="mar10-l ">
            <form class="b-flex flex-vcenter" method="post" action="<?= SITE_URL ?>admin/upload/newdir">
                <input class="form-input mar10-r" type="text" name="dir" placeholder="new dir..." required>
                <button class="button button1 im-plus" type="submit">Create dir</button>
            </form>
        </div>
    </div>

    <form method="post" id="formUpload" enctype="multipart/form-data">
        <input type="hidden" id="upload_max_file_size" name="upload_max_file_size" value="<?= $uploadMaxSize ?>">
        <input type="hidden" id="upload_ext" name="upload_ext" value="<?= $uploadExt ?>">
        <input type="hidden" id="upload_action" name="upload_action" value="<?= $uploadAction ?>">
        <div id="upload_filedrag">... drag and drop files here ...</div>
        <input type="file" id="upload_fileselect" name="upload_fileselect[]" multiple="multiple">
        <div id="upload_submitbutton"><button type="button">Upload Files</button></div>
    </form>

    <div class="pad20-t" id="upload_progress"></div>
    <div class="pad20-t" id="upload_messages"></div>
</div>

<?php
require 'style.css.php';
require 'filedrag.js.php';


# end of file
