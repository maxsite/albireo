<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Навигация next/prev
description: 
parser: simple
slug: doc2
layout: doc.php

menu[title]: Навигация next/prev
menu[group]: Основы
menu[order]: 3

**/

?>

h1(mar0 bg-lime200 pad30-rl pad10-tb) Навигация next/prev

<div class="pad30-rl mar30-tb">
	
	_ Для дополнительной навигации можно использовать сниппет @next-prev@.
    
pre
&lt;?php snippet('next-prev', ['doc', 'doc2']); ?&gt;
/pre
	
    _ Второй параметр (обязательный) задаёт массив из двух элементов. Первый указывает на страницу (на её slug), которая будет выведена ссылкой слева (<i>prev</i>), а второй, аналогично — справа (<i>next</i>).
    
    _ Если это конечные страницы, то один из элементов можно указать в виде пустой строки — ссылка не будет выведена.
    
pre
&lt;?php snippet('next-prev', ['', 'doc1']); ?&gt;
/pre
	
	_ При необходимости вы можете отредактировать файл сниппета, чтобы поменять его дизайн.
	    
    <?php snippet('next-prev', ['doc1', 'doc3']); ?>
    
</div>
