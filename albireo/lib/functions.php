<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

/**
 * Вывести сниппет
 * @param $snippet - имя сниппета
 * snippet('twitter'); // выведет файл albireo-data/snippets/twitter.php
 */
function snippet(string $snippet)
{
    if (file_exists(SNIPPETS_DIR . $snippet . '.php')) require SNIPPETS_DIR . $snippet . '.php';
}

/**
 * Вывод страницы
 */
function pageOut()
{
    $pageData = getVal('pageData'); // данные страницы

    // у страницы может быть свой шаблон
    if (isset($pageData['layout']) and $pageData['layout'] and file_exists(LAYOUT_DIR . $pageData['layout'])) {
        $mainFile = $pageData['layout']; // есть такой файл
    } else {
        // используем тот, который указан в конфигурации
        $mainFile = getConfig('layout');
    }

    // если файл есть
    if ($mainFile and file_exists(LAYOUT_DIR . $mainFile)) {
        ob_start(); // включаем буферизацию
        require LAYOUT_DIR . $mainFile; // подключаем шаблон
        $content = ob_get_contents(); // забрали результат

        if (ob_get_length()) ob_end_clean(); // очистили буфер

        // если указан парсер
        if (isset($pageData['parser']) and $pageData['parser']) {
            $parser = $pageData['parser']; // название парсера
            $parserFile = SYS_DIR . 'lib/' . $parser . '.php'; // файл парсера

            if (file_exists($parserFile))
                require_once $parserFile; // подключили файл

            if (function_exists($parser))
                $content = $parser($content); // обработали текст через функцию парсера
        }

        // сжатие html-кода
        if (isset($pageData['compress']) and $pageData['compress']) {
            require_once SYS_DIR . 'lib/compress.php'; // подключили файл

            $content = compress_html($content); // обработали текст
        }

        echo $content; // вывели контент в браузер
    } else {
        echo 'Error! Main-file not-found... ;-(';
    }
}

/**
 * Получить параметры страницы
 * @param $key - ключ
 * @param $default - значение по умолчанию
 */
function getPageData(string $key,  $default = '')
{
    $pageData = getVal('pageData');

    return $pageData[$key] ?? $default;
}

/**
 * Получить параметры страницы с обработкой HTML
 * @param $key - ключ
 * @param $default - значение по умолчанию
 */
function getPageDataHtml(string $key,  $default = '')
{
    return htmlspecialchars(getPageData($key,  $default));
}

/**
 * Получить значение из файла конфигурации
 * @param $key - ключ
 * @param $default - значение по умолчанию
 */
function getConfig(string $key, $default = '')
{
    static $config = null; // массив данных

    // если он ещё не определен, то считываем из файлов
    if (!$config) {
        $config = [];

        // конфигурация в режие генерации
        if (defined('GENERATE_STATIC')) {
            if (file_exists(CONFIG_DIR . 'config-static.php')) {
                $config = require CONFIG_DIR . 'config-static.php';
            }
        } else {
            // конфигурация сайта по умолчанию
            if (file_exists(CONFIG_DIR . 'config.php')) {
                $config = require CONFIG_DIR . 'config.php';
            }
        }
    }

    return $config[$key] ?? $default;
}

/**
 * Найти под текущий URL файл страницы
 * Результат сохраняется в хранилище
 * $pageFile = getVal('pageFile'); // файл записи
 * $pageData = getVal('pageData'); // данные записи
 */
function matchUrlPage()
{
    $currentUrl = getVal('currentUrl'); // текущий адрес
    $pagesInfo = getVal('pagesInfo'); // все страницы

    $result = ''; // результат

    foreach ($pagesInfo as $file => $page) {
        // вначале проверяем метод
        $method = $page['method'] ?? 'GET'; // по умолчанию это GET
        $method = strtoupper($method); // в верхний регистр

        if ($method == $currentUrl['method']) {
            // если совпал, то смотрим slug
            $slug = $page['slug'] ?? false;

            if (!$slug) continue; // не указан
            if ($slug == '/') $slug = ''; // преобразование для главной

            if (strtolower($slug) == $currentUrl['url']) {
                // есть совпадение
                $result = $file; // имя файла
                break;
            }
        }
    }

    // если ничего не найдено, отдаём файл 404-страницы
    if (!$result and file_exists(DATA_DIR . '404.php')) $result =  DATA_DIR . '404.php';

    // сохраним в хранилище имя файла
    setVal('pageFile', $result);

    // сохраним и данные этой страницы
    setVal('pageData', $pagesInfo[$result] ?? []);

    return $result;
}

/**
 * Получить текущий URL
 * Результат сохраняется в хранилище в ключе «currentUrl»
 * $currentUrl = getVal('currentUrl');
 */
function getCurrentUrl()
{
    // определяем URL
    $relation = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $url = substr_replace($_SERVER['REQUEST_URI'], '', 0, strlen($relation));

    // декодируем по стандарту
    $url = urldecode($url);

    // отсекаем часть ?-get
    if (strpos($url, '?') !== false) {
        $u = explode('?', $url);
        $url = $u[0];
    }

    // удалим правый слэш
    $url = rtrim($url, '/');

    // http-метод
    $method = strtoupper($_SERVER['REQUEST_METHOD']);

    // в POST может быть указан другой метод в поле _method
    if ($method == 'POST' and isset($_POST['_method'])) 
        $method = strtoupper($_POST['_method']);

    // сохраняем в хранилище
    setVal('currentUrl', [
        'method' => $method,
        'url' => $url,
        'urlFull' => SITE_URL . $url,
    ]);
}

/**
 * Считать данные всех записей
 * Результат сохраняется в хранилище в ключе «pagesInfo»
 * $pagesInfo = getVal('pagesInfo');
 */
function readPages()
{
    // смотрим кэш, если есть, отдаем из него
    if ($cache = getCache('pagesinfo.txt')) {
        setVal('pagesInfo', $cache); // сохраняем массив в хранилище

        return; // и выходим
    }

    // все файлы страниц
    $allFiles = glob(DATA_DIR . '*.php');

    // убираем те, которые начинаются с «_»
    $allFiles = array_filter($allFiles, function ($x) {
        if (strpos(basename($x), '_') === 0)
            return false;
        else
            return true;
    });

    $pagesInfo = []; // результирующий массив записей

    // цикл по всем файлам
    foreach ($allFiles as $file) {
        $content = file_get_contents($file); // считали содержимое

        // найдем служебную часть
        // она внутри: /** тут  **/
        if (preg_match('!\/\*\*(.*?)\*\*\/!is', $content, $math)) {
            $content = '?>' . trim($math[1]); // подготовка для eval
            ob_start(); // включили буферизация
            eval($content); // можно использовать PHP-код
            $content = ob_get_contents(); // забрали результат

            if (ob_get_length()) ob_end_clean(); // очистили буфер
        } else {
            $content = ''; // данных нет
        }

        // загоним строчки «key: value» в массив
        if ($content) {
            $a1 = explode("\n", $content); // разделим построчно 

            $info = []; // конечный результат — массив

            foreach ($a1 as $a2) {
                $pos = strpos($a2, ":"); // найдём первое вхождение «:» 

                if ($pos !== false) // если есть, обработаем и в массив результата
                    $info[trim(substr($a2, 0, $pos))] = trim(substr($a2, $pos + 1));
            }

            // если у файла не указано поле slug, то делаем его автоматом равным имени файлу
            if (!isset($info['slug']) or $info['slug'] == '')
                $info['slug'] = str_replace('.php', '', basename($file));

            $pagesInfo[$file] = $info; // сохраним в общих данных
        }
    }

    // сохраняем массив глобально
    setVal('pagesInfo', $pagesInfo);

    // сохраняем данные в кэше — файл pagesinfo.txt
    // данные серилизуем
    file_put_contents(CACHE_DIR . 'pagesinfo.txt', serialize($pagesInfo));
}

/**
 * Получить данные из кэша
 * Кэш устаревает когда были изменения в каталоге DATA_DIR (albireo-data/*)
 * @param $file - имя файла в каталоге кэша
 */
function getCache(string $file)
{
    if (file_exists(CACHE_DIR . $file)) {
        // проверим не устарел ли кэш

        // смотрим время текущего файла
        $timeCache = filemtime(CACHE_DIR . $file);

        // и время всех файлов в каталоге albireo-data
        $allFiles = [];

        // вначале список всех файлов в DATA_DIR
        $files = glob(DATA_DIR . '*');

        // добавляем если есть
        if ($files) $allFiles = $files;

        // теперь смотрим подкаталоги 1-го уровня (больше не нужно)
        $dirs = glob(DATA_DIR . '*', GLOB_ONLYDIR | GLOB_MARK);

        // смотрим в каждом из них файлы и добавляем в общий массив
        foreach ($dirs as $dir) {
            $files = glob($dir . '*');

            if ($files) $allFiles = array_merge($files, $allFiles);
        }

        // находим самый новый файл
        $timeLastModified = 0;

        foreach ($allFiles as $f) {
            $t = filemtime($f);

            // если этот файл новее, то обновляем $timeLastModified
            if ($t > $timeLastModified) $timeLastModified = $t;
        }

        // если кэш оказался старее, то отмечаем его невалидным
        if ($timeLastModified > $timeCache) return false;

        // есть кэш, получаем из него данные
        $content = file_get_contents(CACHE_DIR . $file); // загрузили содержимое

        // обратная серилизация с @подавлением ошибок
        $content = @unserialize($content);

        if ($content)
            return $content; // если есть что отдавать
        else
            return false;
    } else {
        // файла кэша вообще нет
        return false;
    }
}

/**
 * получение данных из хранилища
 * @param $key - ключ
 * @param $default - значение по умолчанию
 */
function getVal(string $key, $default = [])
{
    return storage(false, $key, '', $default);
}

/**
 * запись данных в хранилище
 * @param $key - ключ
 * @param $value - значение
 */
function setVal(string $key, $value)
{
    storage(true, $key, $value, '');
}

/**
 * хранилище данных
 * @param если $set = true, то это запись данных в хранилище
 * @param если $set = fasle, то получение данных из хранилища
 * @param $key - ключ
 * @param $value - значение для записи
 * @param $default - дефолтное значение, если ключ не определён
 */
function storage(bool $set, string $key, $value, $default)
{
    static $data = []; // здесь всё хранится

    if ($set)
        $data[$key] = $value; // запись данных
    else
        return $data[$key] ?? $default; // получение данных
}

/**
 * Create .htaccess
 */
function createHtaccess()
{
    // если файл есть, то ничего не делаем
    if (file_exists(BASE_DIR . '.htaccess')) return;

    // получаем путь сайта на сервере
    if (isset($_SERVER['REQUEST_URI']))
        $path = $_SERVER['REQUEST_URI'];
    else
        $path = '/';

    // считываем шаблон
    $htaccess = file_get_contents(SYS_DIR . 'htaccess-distr.txt');

    // делаем замены
    $htaccess = str_replace('RewriteBase /', 'RewriteBase ' . $path, $htaccess);
    $htaccess = str_replace('RewriteRule . /', 'RewriteRule . ' . $path, $htaccess);

    // сохраняем как .htaccess
    file_put_contents(BASE_DIR . '.htaccess', $htaccess);
}

/**
 * Функция для отладки из MaxSite CMS
 * @param $var - переменная для вывода
 * @param $html - обработать как HTML
 * @param $echo - вывод в браузер
 */
function pr($var, $html = true, $echo = true)
{
    if (!$echo)
        ob_start();
    else
        echo '<pre style="padding: 10px; margin: 10px; background: #455052; color: #D5EAED; white-space: pre-wrap; font-size: 10pt; max-height: 600px; font-family: Consolas, mono; line-height: 1.3; overflow: auto;">';

    if (is_bool($var)) {
        if ($var)
            echo 'TRUE';
        else
            echo 'FALSE';
    } else {
        if (is_scalar($var)) {
            if (!$html) {
                echo $var;
            } else {
                $var = str_replace('<br />', "<br>", $var);
                $var = str_replace('<br>', "<br>\n", $var);
                $var = str_replace('</p>', "</p>\n", $var);
                $var = str_replace('<ul>', "\n<ul>", $var);
                $var = str_replace('<li>', "\n<li>", $var);
                $var = htmlspecialchars($var, ENT_QUOTES);
                $var = wordwrap($var, 300);

                echo $var;
            }
        } else {
            if (!$html) {
                print_r($var);
            } else {
                echo htmlspecialchars(print_r($var, true), ENT_QUOTES);
            }
        }
    }

    if (!$echo) {
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    } else {
        echo '</pre>';
    }
}
        
# end of file
