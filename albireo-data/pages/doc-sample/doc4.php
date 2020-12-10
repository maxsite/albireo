<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Настройка дизайна
description: 
parser: simple
slug: doc4
layout: doc.php

menu[title]: Дизайн
menu[group]: Настройка
menu[order]: 2

**/

?>

h1(mar0 bg-lime200 pad30-rl pad10-tb) Настройка дизайна

<div class="pad30-rl mar30-tb">
	
	h2(mar40-t) Дизайн меню
    
    _ Основной дизайн меню задаётся в файле @config/menu-config.php@. Это массив, где указываеются css-классы для пунктов меню.
    
    h2(mar40-t) Дизайн шаблона
    
    _ Дизайн шаблона формируется в файле @layout/doc.php@. В нём задаётся основное поведение и базовые цвета всего шаблона. Вы можете изменить их на свой вкус.
    
    h2(mar40-t) Внешние файлы шаблона
    
    _ Часть шаблона вынесена в каталог @layout/doc-parts@ в виде отдельных файлов.
    
    ul
    *  @head.php@ — секция HEAD
    *  @nav.php@ — блок верхней навигации колонки меню
    *  @header.php@ — шапка меню
    *  @footer.php@ — подвал меню
    *  @style.php@ — css-стили для секции HEAD
    /ul
    
    <?php snippet('next-prev', ['doc3', '']); ?>
    
</div>
