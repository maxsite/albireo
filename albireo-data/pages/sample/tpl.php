<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Шаблонизатор
description: Пример использования шаблонизатора
slug: tpl
sitemap: -

 **/
?>

<h1 class="t-center mar50-t">Пример использования шаблонизатора</h1>

<?php
    // данные сохраняются в массиве, где ключ — это имя php-переменной, которая будет доступна в файле шаблона (см. _tpl-block.php)
    $data['header'] = 'Заголовок';
    $data['desc'] = 'Описание <b>текст</b>';
?>

<?= tpl(__DIR__ . '/_tpl-block.php', $data) ?>
