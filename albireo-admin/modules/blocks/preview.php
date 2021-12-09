<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Block preview
description: 
slug: admin/blocks/preview/1
slug-pattern: admin/blocks/preview/([0-9]+)
slug-static: -
layout: admin/core/_layout.php
compress: 0

-parser: simple
-head[]: <script src="[admin-url]assets/alpine.min.js"></script>

admin-menu[title]: ---
admin-menu[group]: Modules

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$currentUrl = getVal('currentUrl');
$blockId = (int) str_replace('admin/blocks/preview/', '', $currentUrl['url']);

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl links-no-color"><a href="<?= SITE_URL ?>admin/blocks/<?= $blockId ?>">Block preview #<?= $blockId ?></a></h1>

<div class="mar30-t pad30-rl pad10-rl-tablet pad20-b">

<?php
    // echo Blocks\Blocks::out('top'); // тест

    echo Blocks\Blocks::outID($blockId);
?>

</div>