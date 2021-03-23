<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Log
description: 
slug: admin/log
slug-static: -
parser: simple
compress: 1
layout: admin/core/_layout.php

admin-menu[title]: <i class="im-list-alt"></i>Logging
admin-menu[group]: Modules
admin-menu[order]: 20

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

addClassmap('admin\log', __DIR__); // add dir to classmap

?>

h1(pad20-tb bg-yellow250 pad30-rl) Logging

<div class="pad30-rl pad10-rl-tablet pad20-b">
    <?php
    $log = new admin\log\Controller;
    $log->show();
    ?>
</div>