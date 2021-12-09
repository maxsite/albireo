<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Edit block
description: 
slug: admin/blocks/1
slug-pattern: admin/blocks/([0-9]+)
slug-static: -
-parser: simple
compress: 0
layout: admin/core/_layout.php
head[]: <script src="[admin-url]assets/alpine.min.js"></script>

admin-menu[title]: ---
admin-menu[group]: Modules

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');
$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';


$currentUrl = getVal('currentUrl');
$blockId = (int) str_replace('admin/blocks/', '', $currentUrl['url']);

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl links-no-color"><a href="<?= SITE_URL ?>admin/blocks">Edit block #<?= $blockId ?></a><?= $readOnly ?><a class="t90 b-inline b-right im-address-card t-gray700 hover-t-red700" href="<?= SITE_URL ?>admin/blocks/preview/<?= $blockId ?>" target="_blank">Preview</a></h1>

<div class="mar30-t pad30-rl pad10-rl-tablet pad20-b">
    <?php
    $ctrl = new admin\modules\blocks\mvc\Controller;
    $ctrl->showOne();
    ?>
</div>