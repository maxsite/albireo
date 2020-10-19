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

// определяем флаг этого режима
define('GENERATE_STATIC', 1);

// в этом режиме $_SERVER['HTTP_HOST'] не работает, прописываем свой вариант
// если в стрнаицах не используется константа SITE_URL, то можно не менять
// если используется, то укажите нужный хост и протокол
define('SITE_HOST', 'localhost');
define('SITE_PROTOCOL', 'http');

// подключаем Albireo
require 'index.php';

# end of file