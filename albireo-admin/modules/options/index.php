<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Options
description: 
slug: admin/options
slug-static: -
parser: simple
compress: 0
layout: admin/core/_layout.php
head[]: <script src="[admin-url]assets/alpine.min.js"></script>

admin-menu[title]: <i class="im-cogs"></i>Options
admin-menu[group]: Modules
admin-menu[order]: 30

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');
$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl">Options<?= $readOnly ?></h1>

<?= tpl(__DIR__ . '/tpl/add.php', []) ?>

<?php
// получить данные из flash-сессии, поскольку там хранится результат добавления новой опции
if ($res = sessionFlashGet('options-result')) {
    if (!$res['errors']) {
        // нет ошибок
        echo tpl(__DIR__ . '/tpl/add-no-errors.php', $res);
    } else {
        // есть ошибки
        echo tpl(__DIR__ . '/tpl/add-errors.php', $res);
    }
}
?>

<div class="mar30-t pad30-rl pad10-rl-tablet pad20-b">
    <?php
    $opt = new admin\modules\options\mvc\Controller;
    $opt->show();
    ?>
</div>