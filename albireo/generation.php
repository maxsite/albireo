<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

echo "\nALBIREO FRAMEWORK\n=================\n";
 
require_once SYS_DIR . 'lib/functions.php';

// выходной каталог
$staticDir = getConfig('staticDir');

// если каталог есть, то удалим его, чтобы там ничего не было при новой генерации
if (file_exists($staticDir)) deleteDir($staticDir);

// теперь, если каталога нет, пробуем создать
if (!is_dir($staticDir)) @mkdir($staticDir);

// если не получилось выходим с ошибкой
if (!is_dir($staticDir)) exit('Error: staticDir not found');

// считать данные всех pages
readPages();

echo $staticDir . "\n";

// проходимся по всем страницам
foreach (getVal('pagesInfo') as $file => $pageData) {
    // имя выходного файла задается в slug-static
    $fileOut = $pageData['slug-static'] ?? false;

    // если slug-static: - то ничего не создаём
    if ($fileOut == '-') continue;

    // если его нет, то делаем на основе обычного slug
    if (!$fileOut) {
        $fileOut = $pageData['slug'];

        if ($fileOut == '/') $fileOut = 'index'; // преобразование для главной
        $fileOut .= '.html'; // и добавляем расширение .html
    }

    // подчистка имени файла
    $fileOut = str_replace('\\', '/', $fileOut); // замены для windows
    $fileOut = str_replace(['<', '>', ':', '"',  '|', '?', '*'], '-', $fileOut); // недопустимые символы
    $fileOut = str_replace('//', '/', $fileOut); // двойные слэши
    $fileOut = trim($fileOut, '/'); // удалим крайние слэши

    // итоговый файл с учетом каталога
    $fileWrite = $staticDir . $fileOut;

    // если в имени файла указан каталог то пробуем создать
    if (strpos($fileOut, '/') !== false) {
        // замена для того, чтобы привести слэши к одному виду как принято в ОС
        $fileWrite = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $fileWrite);

        $path = pathinfo($fileWrite); // инфо о файле

        // если нет каталога, то создаём
        if (!file_exists($path['dirname'])) mkdir($path['dirname'], 0777, true);
    }

    // подготовка данных для pageOut(), где формируется страница
    setVal('pageData', $pageData);
    setVal('pageFile', $file);

    // поскольку текущего URL не существует, то иммитируем на основе SITE_URL
    setVal('currentUrl', [
        'method' => 'GET',
        'url' => $fileOut,
        'urlFull' => SITE_URL . $fileOut
    ]);

    // ловим вывод страницы
    ob_start();
    pageOut();
    $content = ob_get_contents(); // забрали результат

    if (ob_get_length()) ob_end_clean(); // очистили буфер

    // сохранили в файл
    file_put_contents($fileWrite, $content);

    // вывели отчёт в консоли
    echo str_replace($staticDir, '   ', $fileWrite) . "\n";
}

// возможно указана настройка в которой хранятся имена каталогов для копирования после генераци
if ($afterCopy = getConfig('afterCopy', [])) {
    echo "\n";
    
    // перебираем их 
    foreach ($afterCopy as $src => $dest) {
        // если исходный каталог действительно существует
        if (file_exists(BASE_DIR . $src)) {
            echo 'Copy dir: ' . $src . "\n"; // вывод в консоли

            copyDir(BASE_DIR . $src, $staticDir . $dest); // копируем каталог
        }
    }
    
    echo "\n";
}

# end of file
