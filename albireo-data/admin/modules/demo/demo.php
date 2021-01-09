<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Demo
description: 
slug: admin/demo
slug-static: -
parser: simple
layout: admin/core/_layout.php

admin-menu[title]: <i class="im-cog"></i>Demo
admin-menu[group]: Modules
admin-menu[order]: 10

**/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

?>

h1(pad20-tb bg-yellow250 pad30-rl) Demo admin module

<div class="pad30-rl pad10-rl-tablet pad20-b">
    _ See <a href="<?= SITE_URL ?>admin/edit/<?= encodeURL64('albireo-data/admin/modules/demo/demo.php')?>">admin/modules/demo/demo.php</a>
</div>
