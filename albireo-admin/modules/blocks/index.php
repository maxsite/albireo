<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Blocks
description: 
slug: admin/blocks
slug-static: -
parser: simple
compress: 0
layout: admin/core/_layout.php
head[]: <script src="[admin-url]assets/alpine.min.js"></script>

admin-menu[title]: <i class="im-clone"></i>Blocks
admin-menu[group]: Modules
admin-menu[order]: 10

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');
$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Blocks<?= $readOnly ?><a class="t90 b-inline b-right im-question-circle t-gray700 hover-t-red700" href="<?= SITE_URL ?>admin/blocks/help">Help</a></h1>

<?= tpl(__DIR__ . '/tpl/add.php', []) ?>

<?php
// получить данные из flash-сессии, поскольку там хранится результат добавления новой опции
if ($res = sessionFlashGet('blocks-result')) {
    echo tpl(__DIR__ . '/tpl/save-result.php', ['message' => $res]);
}
?>

<div class="mar30-t pad30-rl pad10-rl-tablet pad20-b">
    <?php
    $ctrl = new admin\modules\blocks\mvc\Controller;
    $ctrl->show();
    ?>
</div>