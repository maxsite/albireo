<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

/**
 * Начальное время для получения статистики
 *
 * Узнать затраченное время:
 * $time = number_format(microtime(true) - ALBIREO_TIME_START, 6) . 'sec';
 */
define('ALBIREO_TIME_START', microtime(true));

/**
 * Добавить данные во flash-сессию
 * https://maxsite.org/page/php-flash-message
 *
 * @param string $key - ключ
 * @param $val - данные
 */
function sessionFlashSet(string $key, $val)
{
    $_SESSION['_flash'][$key] = $val;
}

/**
 * Получить данные из flash-сессии, после чего данные удалятся
 * @param string $key - ключ
 */
function sessionFlashGet(string $key)
{
    if (isset($_SESSION['_flash'][$key])) {
        $data = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);

        return $data;
    } else {
        return '';
    }
}

/**
 * Шаблонизатор для файла / используется tplContent()
 * @param string $FILE - полное имя файла шаблона
 * @param array $DATA - данные, которые будут доступны в файле в виде переменных
 * @param boolean $showError - отображать ли ошибки в файле шаблона
 * @return string
 *
 * <?= tpl(__DIR__ . '/my-block.php', ['header' => 'Hello!']) ?>
 *
 * В файле шаблона можно использовать обычный php-код, а таже замены:
 * {{ $header }} -> эквивалентно <?= $header ?>
 * {* $header *} -> эквивалентно <?= htmlspecialchars($header, ENT_QUOTES) ?>
 * {% код %} -> эквивалентно <?php код ?>
 *
 * Также будет доступна переменная $DATA (исходный массив данных)
 */
function tpl(string $FILE, array $DATA = [], bool $showError = true)
{
    $FILE = str_replace('\\', '/', $FILE); // замены для windows
    $content = ''; // результат

    // если файл шаблона есть
    if (file_exists($FILE)) {
        $fContent = file_get_contents($FILE); // получаем его содержимое
        $content = tplContent($fContent, $DATA, $showError, $FILE);
    }

    return $content;
}

/**
 * Шаблонизатор для произвольного текста
 * @param string $_Content - текст
 * @param array $DATA - данные, которые будут доступны в файле в виде переменных
 * @param boolean $_showError - отображать ли ошибки в файле шаблона
 * @param boolean $FILE - имя файла для вывода в ошибках
 * @return string
 *
 * В тексте можно использовать обычный php-код, а таже замены:
 * {{ $header }} -> эквивалентно <?= $header ?>
 * {* $header *} -> эквивалентно <?= htmlspecialchars($header, ENT_QUOTES) ?>
 * {% код %} -> эквивалентно <?php код ?>
 * Также будет доступна переменная $DATA (исходный массив данных)
 */
function tplContent(string $_Content, array $DATA = [], bool $_showError = true, string $FILE = '')
{
    // замены шаблонизатора
    $_Content = str_replace(['{*', '*}', '{{', '}}', '{%', '%}'], ['<?= htmlspecialchars(', ', ENT_QUOTES) ?>', '<?= ', ' ?>', '<?php ', ' ?>'], $_Content);

    extract($DATA, EXTR_SKIP); // распаковываем массив в php-переменные

    ob_start(); // включаем буферизацию

    // ловим ошибки
    try {
        eval('?>' . $_Content); // выполняем код
    } catch (Throwable $t) {
        // если возникла ошибка, то выводим сообщение
        if ($_showError) echo '<div>' . $t->getMessage() . ' in <b>' . $FILE . '</b></div>';
    }

    $content = ob_get_contents(); // получаем данные из буфера

    if (ob_get_length()) ob_end_clean(); // очистили буфер

    return $content;
}

/**
 * Скопировать один каталог в другой
 * @param $src - исходный каталог
 * @param $dst - каталог назначения
 * https://www.php.net/manual/ru/function.copy.php#91010
 */
function copyDir(string $src, string $dst)
{
    $dir = opendir($src);
    mkdir($dst);

    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . DIRECTORY_SEPARATOR . $file)) {
                copyDir($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
            } else {
                copy($src . DIRECTORY_SEPARATOR . $file, $dst . DIRECTORY_SEPARATOR . $file);
            }
        }
    }

    closedir($dir);
}

/**
 * Получить данные из файла конфигурации
 * @param $file - файл
 * @param $key - если указан ключ, то возвращаем его массив
 */
function getConfigFile(string $file, $key = '')
{
    if (file_exists($file)) {
        $config = require $file;

        if ($key)
            return $config[$key] ?? [];
        else
            return $config;
    } else {
        return [];
    }
}

/**
 * Получить из pageData ключи вида
 *   key[index1]: val
 *   key[index2]: val
 *
 * @param string $key — искомый ключ
 * @param string $format — html-формат вывода [key] и [val]. Если = false, то отдаётся массив данных
 * @param $pageData — данные страницы. Если false, то получаем автоматом из текущей
 * @return array
 */
function getKeysPageData(string $key = 'meta', string $format = '<meta property="[key]" content="[val]">', $pageData = false)
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
                $vRepl = str_replace('[assets-url]', getConfig('assetsUrl'), $vRepl);
                $vRepl = str_replace('[EOL]', PHP_EOL, $vRepl);
                $vRepl = str_replace('[data-url]', DATA_URL, $vRepl);
                $vRepl = str_replace('[admin-url]', ADMIN_URL, $vRepl);

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
 * @param $data - произвольные данные, которые будут доступны в файле сниппета
 * snippet('twitter'); // выведет файл albireo-data/snippets/twitter.php
 * snippet('nextprev', ['page4', 'page6']);
 */
function snippet(string $snippet, $data = '')
{
    if (file_exists(SNIPPETS_DIR . $snippet . '.php')) require SNIPPETS_DIR . $snippet . '.php';
}

/**
 * Вспомогательная функция подключает файл и получает его результат
 * используется для изоляции файла от остальных функций
 */
function _getContentFile($fn)
{
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
    // проверим существание каталога шаблона
    if (!file_exists(getConfig('templateDir'))) {
        echo 'Error! Template not-found... ;-(';
        return;
    }

    $pageData = getVal('pageData'); // данные страницы

    // у страницы может быть свой шаблон
    $layout = $pageData['layout'] ?? '';
    $mainFile = ''; // итоговый файл

    // если у страницы нет параметра layout, то берём дефолтное значение
    if (!$layout) $layout = getConfig('layout', 'main.php');

    // замена «admin/» на полный путь к файлу
    // это нужно, чтобы обеспечить доступ к админ-панели
    if (strpos($layout, 'admin/') === 0)
        $layout = str_replace('admin/', ADMIN_DIR, $layout);

    // проверяем существование этого файла
    // вначале относительно текущего шаблона
    // потом относительно DATA_DIR
    // потом в DATA_DIR/layout/ для совместимости со строй версией Albireo
    // и потом явно с полным путем (используется в админке)
    if (file_exists(getConfig('templateLayoutDir') . $layout))
        $mainFile = getConfig('templateLayoutDir') . $layout;
    elseif (file_exists(DATA_DIR . $layout))
        $mainFile = DATA_DIR . $layout;
    elseif (file_exists(DATA_DIR . 'layout' . DIRECTORY_SEPARATOR . $layout))
        $mainFile = DATA_DIR . 'layout' . DIRECTORY_SEPARATOR . $layout;
    elseif (file_exists($layout))
        $mainFile = $layout;

    // в конфигурации можно указать файл со своими функциями
    if ($functionsFile = getConfig('functions')) {
        if (file_exists($functionsFile)) require_once $functionsFile;
    }

    // pr($layout); // для отладки
    // pr($mainFile); // для отладки

    // если файл есть
    if ($mainFile and file_exists($mainFile)) {
        // если у страницы есть ключ init-file, то подключаем указанный файл перед шаблоном
        $initFile = $pageData['init-file'] ?? '';

        if ($initFile) {
            // если указан admin/, то меняем на каталог админ-панели
            if (strpos($initFile, 'admin/') === 0)
                $initFile = str_replace('admin/', ADMIN_DIR, $initFile);

            // проверяем файл в каталоге DATA_DIR
            // а потом от корня сайта
            if (file_exists(DATA_DIR . $initFile))
                require_once DATA_DIR . $initFile;
            elseif (file_exists($initFile))
                require_once $initFile;
        }

        // у страницы может быть параметр require[] где перечислены файлы для подключения
        if ($rFiles = getKeysPageData('require', '[val]', $pageData)) {
            foreach ($rFiles as $fn) {
                if (file_exists($fn)) require_once $fn;
            }
        }

        // если у страницы отмечен флаг parser-content-only, то обрабатываем файл страницы отдельно от LAYOUT
        if (isset($pageData['parser-content-only']) and $pageData['parser-content-only']) {
            // файл страницы подключаем отдельно, чтобы его изолировать от текущей функции
            $contentPage = _getContentFile(getVal('pageFile'));

            // обработка контента
            $contentPage = processingContent($contentPage, $pageData);

            // сохраним данные для вывода
            setVal('pageFileContent', $contentPage);

            // подключаем LAYOUT, который уже сам выведет pageFileContent
            $content = _getContentFile($mainFile);
        } else {
            // файл LAYOUT подключаем отдельно, чтобы его изолировать от текущей функции
            $content = _getContentFile($mainFile);

            // обработка контента
            $content = processingContent($content, $pageData);
        }

        // сжатие html-кода выполняется для всей страницы
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
 * Обработка текста через функции - используется в pageOut()
 * @param string $content - текст
 * @param array $pageData - данные страницы
 * @return string
 */
function processingContent(string $content, array $pageData)
{
    // если указан парсер парсеров может быть несколько через пробел
    // чтобы отключить парсер можно указать «-» (минус)
    if (isset($pageData['parser']) and $pageData['parser'] and $pageData['parser'] != '-') {

        $parsers = $pageData['parser']; // название парсеров

        $parsers = explode(' ', $parsers); // список в массив
        $parsers = array_map('trim', $parsers); // обрежем пробелы

        // проходимся по ним
        foreach ($parsers as $parser) {
            $parserFile = SYS_DIR . 'lib/' . $parser . '.php'; // файл парсера

            if (file_exists($parserFile))
                require_once $parserFile; // подключили файл

            if (function_exists($parser))
                $content = $parser($content); // обработали текст через функцию парсера
        }
    }

    // Содержимое PRE и CODE можно заменить на html-сущности
    if (isset($pageData['protect-pre']) and $pageData['protect-pre']) {
        $content = protectHTMLCode($content, $pageData['protect-pre']);
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

    return $content;
}

/**
 * Получить параметры страницы
 * @param $key - ключ
 * @param $default - значение по умолчанию, если нет в данных страницы
 * @param $before - приставка к результату, если он есть
 * @param $after - корень к результату, если он есть
 */
function getPageData(string $key,  $default = '', $before = '', $after = '')
{
    $pageData = getVal('pageData');

    $result = $pageData[$key] ?? $default;

    if ($result) $result = $before . $result . $after;

    return $result;
}

/**
 * Получить параметры страницы с обработкой HTML - аналогично getPageData()
 * @param $key - ключ
 * @param $default - значение по умолчанию, если нет в данных страницы
 * @param $before - приставка к результату, если он есть
 * @param $after - корень к результату, если он есть
 */
function getPageDataHtml(string $key,  $default = '', $before = '', $after = '')
{
    return htmlspecialchars(getPageData($key,  $default, $before, $after));
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

        // автоматически добавляем данные для текущего шаблона        
        $template = $config['template'] ?? 'default';
        $layoutDir = $config['layoutDir'] ?? 'layout';

        $config['templateDir'] = TEMPLATES_DIR . $template . DIRECTORY_SEPARATOR; // путь к шаблону
        $config['templateURL'] = TEMPLATES_URL . $template . '/'; // URL шаблона
        $config['templateLayoutDir'] = TEMPLATES_DIR . $template . DIRECTORY_SEPARATOR . $layoutDir . DIRECTORY_SEPARATOR; // каталог layout

        // URL assets зависит от режима работы Albireo
        if (defined('GENERATE_STATIC'))
            $config['assetsUrl'] = $config['assets'] . '/';
        else
            $config['assetsUrl'] = TEMPLATES_URL . $template . '/' . $config['assets'] . '/';
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

    $query = '';
    $queryData = [];

    // если есть ?-query
    if (strpos($url, '?') !== false) {
        $u = explode('?', $url); // разделяем на два элемента
        $url = $u[0]; // отсекаем часть ?-get

        $query = $u[1]; // вся строчка query

        $qs = str_replace('&amp;', '&', $query); // обработка амперсанда
        $qs = explode('&', $qs); // строку в массив

        // каждый элемент query парсим через parse_str()
        // и заносим в итоговый массив
        foreach ($qs as $val) {
            parse_str($val, $arr);

            foreach ($arr as $key1 => $val1) {
                $queryData[$key1] = $val1;
            }
        }
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
        'query' => $query,
        'queryData' => $queryData,
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
    if ($cache = getCachePagesInfo('pagesinfo')) {
        setVal('pagesInfo', $cache); // сохраняем массив в хранилище
        return; // и выходим
    }

    // основные файлы страниц
    $allFiles = glob(DATA_DIR . '*.php');

    // получаем все php-файлы из указанных каталогов в конфигурации dirsForPages
    // каталоги DATA_DIR/pages и DATA_DIR/admin подключаются всегда
    $addDirs = array_merge([DATA_DIR . 'pages', ADMIN_DIR], getConfig('dirsForPages', []));
    $addFiles = _addFiles($addDirs);

    if ($addFiles) $allFiles = array_merge($allFiles, $addFiles);

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

            $pseudoRand = 1; // иммитируем случайное число в пределах всего массива данных

            foreach ($a1 as $a2) {
                $pos = strpos($a2, ": "); // найдём первое вхождение «: »

                if ($pos !== false) {
                    // если есть, обработаем и в массив результата

                    // ключ
                    $k = trim(substr($a2, 0, $pos));

                    // если в ключе есть [], то заменим на [*$pseudoRand], чтобы обеспечить его уникальность
                    // используется для короткой записи, чтобы не указывать уникальный ключ
                    // head[]: что-то
                    // head[]: ещё что-то
                    $k = str_replace('[]', '[-' . $pseudoRand++ . ']', $k);

                    $info[$k] = trim(substr($a2, $pos + 1));
                }
            }

            // если у файла не указано поле slug, то делаем его автоматом
            if (!isset($info['slug']) or $info['slug'] == '') {

                // пути нужны относительно DATA_DIR
                // возможно, что файл в подкаталоге pages
                $f = str_replace(DATA_DIR . 'pages' . DIRECTORY_SEPARATOR, '', $file);

                // или в самом DATA_DIR
                $f = str_replace(DATA_DIR, '', $f);

                // но, если это другой каталог, то ставим slug относительно BASE_DIR
                $f = str_replace(BASE_DIR, '', $f);

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
    $cache = Services\Services::getInstance()->get('Cache\Cache');

    // когда доступнен класс кэширования, сохраним
    if ($cache !== null) $cache->set('pagesinfo', $pagesInfo);
}

/**
 * Получить все php-файлы из указанных каталогов
 * @param array $dirs - список каталогов
 **/
function _addFiles(array $dirs)
{
    $out = [];

    foreach ($dirs as $dir) {
        $files = [];

        if (is_dir($dir)) {
            // сортировка по каталогам, а потом по файлам
            // используется свой вспомогательный класс PageSortedIterator
            $directory = new \RecursiveDirectoryIterator($dir);
            $iterator = new \RecursiveIteratorIterator($directory);
            $sit = new \PageSortedIterator($iterator);


            foreach ($sit as $info) {
                // добавляем только php-файлы
                if ($info->isFile() and $info->getExtension() == 'php') $files[] = $info->getPathname();
            }
        }

        if ($files) $out = array_merge($out, $files);
    }

    return $out;
}

/**
 * Вспомогательный класс для сортировки в readPages()
 */
class PageSortedIterator extends SplHeap
{
    public function __construct(Iterator $iterator)
    {
        foreach ($iterator as $item) {
            $this->insert($item);
        }
    }
    public function compare($b, $a): int
    {
        return strcmp($a->getRealpath(), $b->getRealpath());
    }
}

/**
 * Получить данные страниц из кэша
 * Кэш устаревает когда были изменения в каталоге DATA_DIR (albireo-data/*) и ADMIN_DIR
 * @param $key - имя файла в кэше без расширения
 */
function getCachePagesInfo(string $key)
{
    // если в конфигурации ключ noCache = true, то кэш отключаем (режим отладки)
    if (getConfig('noCache', false)) return false;

    // формируем объект кэша - он настраивается в конфигурации
    // используем контейнер, поскольку класс Cache\Cache нам нужен в единственном экземпляре
    $cache = Services\Services::getInstance()->get('Cache\Cache');

    // не доступнен класс кэширования
    if ($cache === null) return false;

    // файла кэша вообще нет, выходим
    if (!$cache->has($key)) return false;

    // проверим не устарел ли кэш

    // смотрим когда было последнее обращение к этому snapshot
    // он хранится в файле lastXXX.txt
    // если текущая дата отличается от времени в last больше чем на cacheTimeL1 секунд,
    // то проверим snapshot как обычно
    // если разница меньше, то считаем, что кэш не устарел и отдаём его без проверки snapshot
    // время указывается в конфигурации сайта в ключе cacheTimeL1 в секундах
    // это позволяет уменьшить количество обращений к диску при большом количестве http-запросов

    // время по умолчанию 10 секунд
    $cacheTimeL1 = (int) getConfig('cacheTimeL1', 10);

    // имя файла last формируем динамически с привязкой к файлу кэша
    $lastFN = 'last' . crc32($key);

    if ($cacheTimeL1 > 0)
        $lastOld = $cache->get($lastFN, 0);
    else
        $lastOld = 0; // здесь будет время из этого файла

    if (time() - $lastOld > $cacheTimeL1) {
        // кэш snapshot возможно устарел, требуется проверка

        // смотрим «снимок» каталогов, включая DATA_DIR, ADMIN_DIR и свои каталоги
        // это позволяет отслеживать все изменения
        $addDirs = array_merge([DATA_DIR], [ADMIN_DIR], getConfig('dirsForPages', []));
        $snapshot = getSnapshot($addDirs); // текущий «снимок»

        // сравниваем старый (из кэша) и новый «снимки»

        // получаем CRC32 полином
        $snapshot = crc32($snapshot);

        // старый берём из кэша
        // имя файла snapshot формируем динамически с привязкой к файлу кэша
        $snapshotFN = 'snapshot' . crc32($key);
        $snapshotOld = $cache->get($snapshotFN, 0);

        // обновляем время построения текущего «снимка»
        $cache->set($lastFN, time());

        // если они не равны, то кэш невалидный
        if ($snapshot != $snapshotOld) {
            // сохраняем в кэше новый
            $cache->set($snapshotFN, $snapshot);

            // кэш невалидный, выходим
            return false;
        }
    }

    // если всё, ок, то отдаём кэш из файла
    return $cache->get($key, false);
}

/**
 * Создание snapshot из файлов указанных каталогов (включая вложенные)
 * @param array $dirs - каталоги
 * @return string
 */
function getSnapshot(array $dirs)
{
    $snapshot = '';

    foreach ($dirs as $dir) {

        if (!is_dir($dir)) continue;

        // рекурсивно обходим каталог
        $directory = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            // в «снимок» идут имена файлов и их даты
            // и только с расширением php
            if ($info->getExtension() == 'php')
                $snapshot .= $info->getPathname() . $info->getMTime();
        }
    }

    return $snapshot;
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
 * @param если $set = false, то получение данных из хранилища
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
 * @param $mode - режим замены 1 - <PRE> и <CODE>, 2 - только <PRE>, 3 - только <CODE>
 */
function protectHTMLCode(string $text, $mode = '1')
{
    if ($mode == '1' or $mode == '2') {
        $text = preg_replace_callback('!(<pre.*?>)(.*?)(</pre>)!is', function ($m) {
            $t = htmlspecialchars($m[2]); // в html-сущности
            $t = str_replace('&amp;', '&', $t); // амперсанд нужно вернуть назад, чтобы иметь возможность его использовать в тексте
            return $m[1] . $t . $m[3];
        }, $text);
    }

    if ($mode == '1' or $mode == '3') {
        $text = preg_replace_callback('!(<code.*?>)(.*?)(</code>)!is', function ($m) {
            $t = htmlspecialchars($m[2]);
            $t = str_replace('&amp;', '&', $t);
            return $m[1] . $t . $m[3];
        }, $text);
    }

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

/**
 * Добавить файл/каталог к карте php-классов, который будут загружен в spl_autoload_register()
 * Используется для классов вне PSR-4
 * @param string $class — имя класса
 * @param string $path — полный путь к файлу или каталогу
 *
 * addClassmap('log\Model', __DIR__ . '/Model.php');
 * $m = new log\Model;
 *
 * addClassmap('admin\log', __DIR__);
 * $m = new admin\log\Controller; // admin/log/Controller.php
 * $m = new admin\log\Model; // admin/log/Model.php
 * $v = new admin\log\View; // admin/log/View.php
 *
 */
function addClassmap(string $class, string $path)
{
    $map = getVal('_classmap', []);
    $map[$class] = $path;
    setVal('_classmap', $map);
}

/**
 * Autoload Composer
 * Располагается в корне сайта в каталоге vendor
 */
if (file_exists(BASE_DIR . 'vendor/autoload.php')) require_once BASE_DIR . 'vendor/autoload.php';

/**
 * Register autoload classes PSR-4
 * https://www.php-fig.org/psr/psr-4/
 * https://maxsite.org/page/php-autoload
 *
 * Файл класса располагается в либо в albireo/psr4
 * либо в albireo-data (DATA_DIR и имеет приоритет)
 * Либо используется classmap (см. addClassmap)
 *
 * Каталог указывает на namespace
 *
 * Пример 1
 * File: albireo/psr4/Pdo/PdoConnect.php
 *   namespace Pdo;
 *   class PdoConnect {...}
 *
 *   $t = new Pdo\PdoConnect;
 *
 * Пример 2
 * File: albireo-data/myLib/myClass.php
 *   namespace myLib;
 *   class myClass {...}
 *
 *   $t = new myLib\myClass;
 *
 */
spl_autoload_register(function ($class) {
    // разбиваем класс на элементы
    $namespace = explode('\\', $class);

    // получаем имя файла из имени класса - в $namespace окажется только namespace
    $file = array_pop($namespace) . '.php';

    // получаем путь на основе namespace
    $path = implode(DIRECTORY_SEPARATOR, $namespace);

    // формируем имя файла
    $fn0 = $path . DIRECTORY_SEPARATOR . $file;
        
    // если путь класс начинается с «admin\», то меняем его на каталог админки
    if (strpos($fn0, 'admin' . DIRECTORY_SEPARATOR) === 0)
        $fn0 = str_replace('admin' . DIRECTORY_SEPARATOR, ADMIN_N . DIRECTORY_SEPARATOR, $fn0);

    // добавляем базовый путь DATA_DIR и SYS_DIR
    $fn1 = DATA_DIR . $fn0; // путь от albireo-data/
    $fn2 = SYS_DIR . 'psr4' . DIRECTORY_SEPARATOR . $fn0; // путь от albireo/psr4/
    $fn3 = BASE_DIR . $fn0; // путь от корня

    // для теста, если интересно что в итоге получается
    // pr($class); // admin\modules\options\mvc\Controller
    // pr($fn0);   // admin\modules\options\mvc\Controller.php
    // pr($fn1);   // albireo\my\albireo-data\admin\modules\options\mvc\Controller.php
    // pr($fn2);   // albireo\my\albireo\psr4\admin\modules\options\mvc\Controller.php
    // pr($fn3);   // albireo\my\albireo\psr4\admin\modules\options\mvc\Controller.php

    // проверка на существование файлов и подключение
    if (file_exists($fn1)) require $fn1;
    elseif (file_exists($fn2)) require $fn2;
    elseif (file_exists($fn3)) require $fn3;
    else {
        // проверка в classmap
        // это может быть как файл, так и каталог (равен namespace)
        if ($map = getVal('_classmap', [])) {
            // формируем namespace из массива
            $ns = implode('\\', $namespace);

            // путь получаем из $map
            $path = $map[$ns] ?? false;

            // проверяем есть ли что-то по этому пути
            if ($path and file_exists($path)) {
                if (is_file($path)) {
                    require_once $path; // это файл
                } elseif (is_dir($path)) {
                    // это каталог
                    $fn3 = $path . DIRECTORY_SEPARATOR . $file;

                    if (file_exists($fn3)) require_once $fn3;
                }
            }
        }
    }
});

# end of file
