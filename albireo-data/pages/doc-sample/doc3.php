<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Настройка меню
description: 
parser: simple
slug: doc3
layout: doc.php

menu[title]: Меню
menu[group]: Настройка
menu[order]: 1

**/

?>

h1(mar0 bg-lime200 pad30-rl pad10-tb) Настройка меню

<div class="pad30-rl mar30-tb">
	
    _(t-italic) Предусмотрено два варианта вывода меню. Первый — автоматический, второй — ручной.

	h2(mar40-t) Автоматический вывод меню

	_ Этот вариант используется по умолчанию. Данные меню формируются на основе параметров страниц @menu@.
	
pre
...
menu[title]: Управление
menu[group]: Основы
menu[order]: 1
...
/pre

    _ Albireo самостоятельно найдёт нужные страницы для вывода.
    
    _ Задать порядок групп можно в файле @config/menu-config.php@ в опции @groupOrder@.
    
    h2(mar40-t) Ручной вывод меню
    
    _ Этот вариант может использоваться для случаев, если пункты меню нужно указывать вручную. Для его включения, следует в файле @layout/doc.php@ строчку

pre
&lt;?php require LAYOUT_DIR . 'doc-parts/menu.php'; ?&gt;
/pre

    _ заменить на
    
pre
&lt;?php require LAYOUT_DIR . 'doc-parts/menu-manual.php'; ?&gt;
/pre
    
    _ После этого параметры страниц @menu@ будут игнорироваться, а пункты можно задать в файле @config/menu-data.php@.
    
    <?php snippet('next-prev', ['doc2', 'doc4']); ?>
    
</div>
