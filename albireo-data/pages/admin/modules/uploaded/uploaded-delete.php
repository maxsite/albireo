<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: @ uploaded-delete
description: 
slug: admin/uploaded/delete
slug-pattern: admin/uploaded/delete(.*?)
slug-static: -
layout: empty.php
parser: -
protect-pre: 0
compress: 0
init-file: pages/admin/core/_functions.php

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$currentUrl = getVal('currentUrl');
$lastSegment = basename($currentUrl['url']);
$file = decodeURL64($lastSegment);

if (verifyLogin(['admin-change-files'])) {
    if ($file and file_exists(BASE_DIR . $file)) unlink(BASE_DIR . $file);
}

header('Location: ' . SITE_URL . 'admin/uploaded');

#end of file
