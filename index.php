<?php

/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// основной каталог
define('BASE_DIR', dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR);

// можно переопределить дальнейшие константы в файле config.php
if (file_exists('config.php')) require 'config.php'; // custom config

// определяем http-протокол
if (!defined('SITE_PROTOCOL')) {
    $protocol = ((isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != "off") ? 'https' : 'http');
    define('SITE_PROTOCOL', $protocol);
    unset($protocol);
}

// определяем http-хост
if (!defined('SITE_HOST')) {
    $host = rtrim($_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']), '/');
    define('SITE_HOST', $host);
    unset($host);
}

// базовые константы
if (!defined('SITE_URL')) define('SITE_URL', SITE_PROTOCOL . '://' . SITE_HOST . '/');
if (!defined('SYS_DIR')) define('SYS_DIR', BASE_DIR . 'albireo' . DIRECTORY_SEPARATOR);
if (!defined('CACHE_DIR')) define('CACHE_DIR', SYS_DIR . 'cache' . DIRECTORY_SEPARATOR);
if (!defined('DATA_DIR')) define('DATA_DIR', BASE_DIR . 'albireo-data' . DIRECTORY_SEPARATOR);
if (!defined('DATA_URL')) define('DATA_URL', SITE_URL . 'albireo-data/');
if (!defined('CONFIG_DIR')) define('CONFIG_DIR', DATA_DIR . 'config' . DIRECTORY_SEPARATOR);
if (!defined('LAYOUT_DIR')) define('LAYOUT_DIR', DATA_DIR . 'layout' . DIRECTORY_SEPARATOR);
if (!defined('SNIPPETS_DIR')) define('SNIPPETS_DIR', DATA_DIR . 'snippets' . DIRECTORY_SEPARATOR);
if (!defined('STATIC_EXT')) define('STATIC_EXT', '');

// в зависимости от режима, подключаем разные файлы
if (defined('GENERATE_STATIC'))
    require SYS_DIR . 'generation.php';
else
    require SYS_DIR . 'albireo.php';

# end of file
