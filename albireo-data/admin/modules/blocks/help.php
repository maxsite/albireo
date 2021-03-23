<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Blocks Help
description: 
slug: admin/blocks/help
slug-static: -
parser: simple
compress: 1
layout: admin/core/_layout.php
head[]: <script src="[data-url]admin/assets/alpine.min.js"></script>

admin-menu[title]: ---
admin-menu[group]: Modules

 **/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

?>

<h1 class="pad20-tb bg-yellow250 pad30-rl links-no-color"><a href="<?= SITE_URL ?>admin/blocks">Blocks Help</a></h1>

<div class="mar30-t pad30-rl pad10-rl-tablet pad20-b">

Блоки хранятся в базе.

blocks_id — автоинкремент
blocks_key - название блока — могут повторяться. Пусть лучше выводятся все сколько есть.
blocks_info — информация о блоке, описание и т.д. обычный текст
blocks_parser - используемый парсер при выводе blocks_content (simple или пусто)
blocks_usephp - выполнять php-код внутри блока — нет или php/tpl
blocks_group1 - произвольная группа блока, например Content
blocks_group2 - произвольная группа блока, например Бесплатно
blocks_group3 - произвольная группа блока, например MF
blocks_order - номер по порядку. например для вывода в отпределенной последовательности. по этому полю делать ORDER выборку при отображенни в админке и на сайте (если используются одноименные blocks_key)

blocks_content - основное содержимое блока
blocks_start - содержимое до основного (например скрипты и т.п.)
blocks_end - содержимое после основного

— подумать
blocks_vars - какие-то опции для текущего блока — делаются замены 

	blocks_content:
		<div>[header]</div>
		<div>@description</div>
	
	blocks_vars
		[header] = Заголовок
		@description = Описание

---------

Вывод на сайте:
echo Blocks\Blocks\out('название блока');
echo Blocks\Blocks\outID(7); // номер блока



</div>