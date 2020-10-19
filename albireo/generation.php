<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

require_once SYS_DIR . 'lib/functions.php';

// выходной каталог
$staticDir = getConfig('staticDir');

// если его нет, пробуем создать
if (!is_dir($staticDir)) @mkdir($staticDir);

// если не получилось выходим с ошибкой
if (!is_dir($staticDir)) exit('Error: staticDir not found');

// считать данные всех pages
readPages(); 

// проходимся по всем страницам
foreach (getVal('pagesInfo') as $file => $pageData) {
    // имя выходного файла задается в slug-static
    $fileOut = $pageData['slug-static'] ?? false;

    // если slug-static: - то ничего не создаём
    if ($fileOut === '-') continue;

    // если его нет, то делаем на основе обычного slug
    if (!$fileOut) {
        $fileOut = $pageData['slug'];
        if ($fileOut == '/') $fileOut = 'index'; // преобразование для главной
        $fileOut .= '.html'; // и добавляем расширение .html
    }

    // если указан «/», то меняем его на «-»
    $fileOut = str_replace('/', '-', $fileOut);

    // подготовка данных для pageOut(), где формируется страница
    setVal('pageData', $pageData); 
    setVal('pageFile', $file);

    // ловим вывод страницы
    ob_start();
    pageOut();
    $content = ob_get_contents(); // забрали результат
    if (ob_get_length()) ob_end_clean(); // очистили буфер

    // сохранили в файл
    file_put_contents($staticDir . $fileOut, $content);

    // вывели отчёт в консоли
    echo $staticDir . $fileOut . "\n";
}

# end of file
