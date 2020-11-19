<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Demo
description: 
slug: admin/demo
slug-static: -
parser: simple
layout: pages/admin/core/_layout.php

menu[title]: <i class="im-cog"></i>Demo
menu[group]: Modules
menu[order]: 10

**/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

?>

h1(pad20-tb bg-yellow250 pad30-rl) Demo admin module

<div class="pad30-rl pad10-rl-tablet pad20-b">
    _ See <a href="<?= SITE_URL ?>admin/edit/<?= encodeURL64('pages/admin/modules/demo/demo.php')?>">pages/admin/modules/demo/demo.php</a>
</div>
