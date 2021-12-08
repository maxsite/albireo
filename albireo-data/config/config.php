<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// конфигурация для обычного вывода страниц

return [
    // url-путь к assets
    'assetsUrl' => SITE_URL . 'assets/',

    // шаблон по умолчанию
    'layout' => 'main.php',

    // каталоги, где ещё могут располагаться страницы, кроме DATA_DIR
    // указывается полный путь
    // 'dirsForPages' => [BASE_DIR . 'my-pages'],

    // файл для всех страниц
    // 'functions' => DATA_DIR . 'my/my.php',

    // отключить кэширование — только для отладки
    // 'noCache' => true,

    /*
    // настройки кэша
    // класс выполняющий хранение кэша
    'cache' => 'Cache\Handlers\File', // по умолчанию используется файловый кэш

    // конфигурация для классов кэша
    'cacheHandlers' => [
        'Cache\Handlers\File' => [
            'path' => CACHE_DIR,
            'ext' => '.txt',
        ],

        // на будущее
        // 'Cache\Handlers\Memcached' => [],
        // 'Cache\Handlers\Sqlite' => [],
    ],
    */
];

# end of file
