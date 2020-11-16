<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

/**
 * Получить из pageData ключи вида
 *   key[index1]: val
 *   key[index2]: val
 *
 * @param $key — искомый ключ
 * @param $format — html-формат вывода [key] и [val]. Если = false, то отдаётся массив данных
 * @param $pageData — данные страницы. Если файле, то получаем автоматом из текущей
 * @return array
 */
function getKeysPageData($key = 'meta', $format = '<meta property="[key]" content="[val]">', $pageData = false)
{
    $out = []; // выходной массив

    // если нет $pageData, то получаем данные из текущей страницы
    if ($pageData === false) $pageData = getVal('pageData'); // данные страницы

    // проходимся по данным страницы
    foreach ($pageData as $k => $v) {
        // ищем шаблон поиска в ключе массива
        if (preg_match('!^' . $key . '\[(.*?)\]$!is', $k, $m)) {
            // есть совпадение

            // если указан выходной html-формат, то используем его
            if ($format) {
                // для value сделаем спецзамены
                $vRepl = $v;

                $vRepl = str_replace('[page-description]', getPageDataHtml('description'), $vRepl);
                $vRepl = str_replace('[page-title]', getPageDataHtml('title'), $vRepl);
                $vRepl = str_replace('[page-slug]', rtrim(getPageDataHtml('slug'), '/'), $vRepl);
                $vRepl = str_replace('[site-url]', SITE_URL, $vRepl);

                $out[] = str_replace(['[key]', '[val]'], [$m[1], $vRepl], $format);
            } else {
                $out[$m[1]] = $v;
            }
        }
    }

    return $out;
}

/**
 * Удаление каталога и всех его файлов
 * https://www.php.net/manual/ru/function.rmdir.php#110489
 *
 * @param $dir - удаляемый каталог
 */
function deleteDir(string $dir)
{
    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteDir("$dir/$file") : unlink("$dir/$file");
    }

    return rmdir($dir);
}

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
 * Функция подключает файл и получает его результат
 * используется для изоляции файла от остальных функций
*/
function _getContentFile($fn) {
    ob_start(); // включаем буферизацию
    require $fn; // подключаем файл
    $content = ob_get_contents(); // забрали результат
    
    if (ob_get_length()) ob_end_clean(); // очистили буфер

    return $content;
}

/**
 * Вывод страницы
 */
function pageOut()
{
    $pageData = getVal('pageData'); // данные страницы

    // у страницы может быть свой шаблон
    $layout = $pageData['layout'] ?? '';
    $mainFile = ''; // итоговый файл

    if ($layout) {
        // приоритет файла в LAYOUT_DIR
        if (file_exists(LAYOUT_DIR . $layout)) {
            $mainFile = LAYOUT_DIR . $layout; // есть такой файл
        } else {
            // возможно файл указан относительно каталога DATA_DIR
            if (file_exists(DATA_DIR . $layout)) $mainFile = DATA_DIR . $layout;
        }
    }

    // если ничего не нашли, то используем тот, который указан в конфигурации
    if (!$mainFile) $mainFile = LAYOUT_DIR . getConfig('layout');

    // в конфигурации можно указать файл со своими функциями
    if ($functionsFile = getConfig('functions')) {
        if (file_exists($functionsFile)) require_once $functionsFile;
    }

    // если файл есть
    if ($mainFile and file_exists($mainFile)) {
        // если у страницы есть ключ init-file, то подключаем указанный файл перед шаблоном
        if (isset($pageData['init-file']) and $pageData['init-file'] and file_exists(DATA_DIR . $pageData['init-file'])) {
            require DATA_DIR . $pageData['init-file'];
        }

        // файл подключаем отдельно, чтобы его изолировать от текущей функции
        $content = _getContentFile($mainFile);

        // если указан парсер
        // чтобы отключить парсер можно указать «-» (минус)
        if (isset($pageData['parser']) and $pageData['parser'] and $pageData['parser'] != '-') {
            $parser = $pageData['parser']; // название парсера
            $parserFile = SYS_DIR . 'lib/' . $parser . '.php'; // файл парсера

            if (file_exists($parserFile))
                require_once $parserFile; // подключили файл

            if (function_exists($parser))
                $content = $parser($content); // обработали текст через функцию парсера
        }

        // Содержимое PRE и CODE можно заменить на html-сущности
        if (isset($pageData['protect-pre']) and $pageData['protect-pre']) {
            $content = protectHTMLCode($content);
        }

        // произвольные php-функции для обработки контента
        // функция должна принимать только один параметр
        // функций может быть несколько через пробел
        // если функция недоступна она игнорируется
        // text-function: trim
        if (isset($pageData['text-function']) and $tf = $pageData['text-function']) {
            $tf = explode(' ', $tf); // список в массив
            $tf = array_map('trim', $tf); // обрежем пробелы

            // проходимся по ним
            foreach ($tf as $f) {
                // если функция есть, то выполняем её
                if (function_exists($f)) $content = $f($content);
            }
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

            if (!$slug) continue; // не указан, но должен быть
            if ($slug == '/') $slug = ''; // преобразование для главной

            if (strtolower($slug) == $currentUrl['url']) {
                // есть совпадение
                $result = $file; // имя файла
                break;
            } else {
                // slug не совпал, поэтому смотрим поле slug-pattern, где может храниться регулярка
                $slug_pattern = $page['slug-pattern'] ?? false;

                if ($slug_pattern) {
                    if (preg_match('~^' . $slug_pattern . '$~iu', $currentUrl['url'])) {
                        // есть совпадение
                        $result = $file; // имя файла
                        break;
                    }
                }
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

    // подчистка адреса от XSS-атаки

    // удалим все тэги
    $url = strip_tags($url);

    // удалим «опасные» символы - в адресе нельзя их использовать
    $url = str_replace(['<', '>', '"', "'", '(', '  {', '['], '', $url);

    // амперсанд меняем на html-вариант
    $url = str_replace('&', '&amp;', $url);

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

    // основные файлы страниц
    $allFiles = glob(DATA_DIR . '*.php');

    // если есть каталог pages, то проходимся по нему в рекурсивном режиме
    $pages = [];

    if (is_dir(DATA_DIR . 'pages')) {
        $directory = new \RecursiveDirectoryIterator(DATA_DIR . 'pages');
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            if ($info->isFile() and $info->getExtension() == 'php') $pages[] = $info->getPathname();
        }
    }

    // если файлы в pages найдены, то объединяем их с основными
    if ($pages) $allFiles = array_merge($allFiles, $pages);

    // убираем те, которые начинаются с «_» и «.»
    $allFiles = array_filter($allFiles, function ($x) {
        if (strpos(basename($x), '_') === 0 or strpos(basename($x), '.') === 0)
            return false;
        else
            return true;
    });

    $pagesInfo = []; // результирующий массив записей

    // в конфигурации может быть ключ defaultPageData — массив с данными по умолчанию
    // они объединяются с каждой страницей
    if ($defaultInfo = getConfig('defaultPageData', [])) {
        if (!is_array($defaultInfo)) $defaultInfo = [];
    }

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

            // конечный результат — массив с дефолтными данными
            $info = $defaultInfo;

            foreach ($a1 as $a2) {
                $pos = strpos($a2, ": "); // найдём первое вхождение «: »

                if ($pos !== false) // если есть, обработаем и в массив результата
                    $info[trim(substr($a2, 0, $pos))] = trim(substr($a2, $pos + 1));
            }

            // если у файла не указано поле slug, то делаем его автоматом
            if (!isset($info['slug']) or $info['slug'] == '') {

                // пути нужны относительно DATA_DIR
                // возможно, что файл в подкаталоге pages
                $f = str_replace(DATA_DIR . 'pages' . DIRECTORY_SEPARATOR, '', $file);

                // или в самом DATA_DIR
                $f = str_replace(DATA_DIR, '', $f);

                // инфо о файле
                $parts = pathinfo($f);


                // берём только путь и имя файла без расширения
                $slug =  $parts['dirname'] . DIRECTORY_SEPARATOR . $parts['filename'];

                // делаем замены слэшей на URL
                $slug = str_replace(['.\\', './', '\\'], ['', '', '/'], $slug);

                $info['slug'] = $slug; // готовый slug
            }

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
    // если в конфигурации ключ noCache = true, то кэш отключаем (режим отладки)
    if (getConfig('noCache', false)) return false;

    if (file_exists(CACHE_DIR . $file)) {
        // проверим не устарел ли кэш

        // смотрим время текущего файла
        $timeCache = filemtime(CACHE_DIR . $file);

        // и время всех файлов в каталоге albireo-data
        $allFiles = [];

        // рекурсивно обходим каталог DATA_DIR
        $directory = new \RecursiveDirectoryIterator(DATA_DIR);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            // добавляем только если это файл
            if ($info->isFile()) $allFiles[] = $info->getPathname();
        }

        // находим самый новый файл
        $timeLastModified = 0;

        foreach ($allFiles as $f) {
            $t = filemtime($f); // время модификации файла

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
 * Преобразуем текст тэгов PRE и CODE в html-сущности
 * @param $text - входящий текст
 */
function protectHTMLCode(string $text)
{
    $text = preg_replace_callback('!(<pre.*?>)(.*?)(</pre>)!is', function ($m) {
        $t = htmlspecialchars($m[2]); // в html-сущности
        $t = str_replace('&amp;', '&', $t); // амперсанд нужно вернуть назад, чтобы иметь возможность его использовать в тексте
        return $m[1] . $t . $m[3];
    }, $text);

    $text = preg_replace_callback('!(<code.*?>)(.*?)(</code>)!is', function ($m) {
        $t = htmlspecialchars($m[2]);
        $t = str_replace('&amp;', '&', $t);
        return $m[1] . $t . $m[3];
    }, $text);

    return $text;
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
