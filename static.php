<?php
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 * 
 * Запуск генерации статичных файлов
 * Работает только через командную строку
 *     php static.php
 * 
 */

// разрешить запуск только в режиме CLI
if (PHP_SAPI != 'cli') exit("It only works in CLI mode!\n");

// задаём флаг этого режима
define('GENERATE_STATIC', 1);

// в этом режиме $_SERVER['HTTP_HOST'] не работает, прописываем свой вариант
// если в страницах не используется константа SITE_URL, то можно не менять
// если используется, то укажите нужный хост, протокол и адрес
define('SITE_HOST', '');
define('SITE_PROTOCOL', '');
define('SITE_URL', '');

// расширение файлов по умолчанию в режиме генерации
// может использоваться как дополнение к ссылкам site/about< ?= STATIC_EXT ? >
// в обычном режиме равен пустой строке
define('STATIC_EXT', '.html');

// подключаем Albireo
require 'index.php';

# end of file