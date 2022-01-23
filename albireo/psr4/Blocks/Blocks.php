<?php

/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2021
 *
 * Вывод блоков
 *
 * Использование:
 * echo Blocks\Blocks::out('top');
 * echo Blocks\Blocks::outID(7);
 *
 */

namespace Blocks;

class Blocks
{
    /**
     * Вывод блоков по имени (ключу)
     * @param string $key - имя блоков
     * @return string
     */
    public static function out(string $key): string
    {
        if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'blocks')) {
            $pdo = \Pdo\PdoConnect::getInstance();
            $db = $pdo->connect($configDB);
        }

        if (empty($db)) return '';

        // одноименных блоков может быть несколько, поэтому получаем все, потом в цикле выводим
        // порядок получения совпадает с order, потом id, ASC
        $rows = \Pdo\PdoQuery::fetchAll(
            $db,
            'SELECT * FROM blocks WHERE blocks_key = :key ORDER BY blocks_order, blocks_id',
            [':key' => $key]
        );

        if ($rows) {
            $out = '';

            foreach ($rows as $row) {
                $out .= self::outBlock($row);
            }

            return $out;
        } else {
            return '';
        }
    }


    /**
     * Вывод блока по его ID
     * @param int $id - id блока
     * @return string
     */
    public static function outId(int $id): string
    {
        if ($id < 1)  return '';

        if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'blocks')) {
            $pdo = \Pdo\PdoConnect::getInstance();
            $db = $pdo->connect($configDB);
        }

        if (empty($db)) return '';

        $rows = \Pdo\PdoQuery::fetchAll(
            $db,
            'SELECT * FROM blocks WHERE blocks_id = :id',
            [':id' => $id]
        );

        if ($rows)
            return self::outBlock($rows[0]);
        else
            return '';
    }

    /**
     * Вывод блока по массиву его данных
     * @param array $block - данные блока
     * @return string
     */
    public static function outBlock(array $block): string
    {
        // получим весь текст
        $out = $block['blocks_start'] . $block['blocks_content'] . $block['blocks_end'];

        // сделаем замены blocks_vars
        // переменная = значение
        // разделитель это « = »
        if ($vars = $block['blocks_vars']) {

            $vars = str_replace("\r", '', $vars); // windows
            $a1 = explode("\n", $vars); // разделим построчно
            $res = []; // итоговый массив переменных блока

            foreach ($a1 as $a2) {
                $pos = strpos($a2, ' = '); // найдём первое вхождение « = »

                if ($pos !== false) {
                    // если есть, обработаем и в массив
                    $k = trim(substr($a2, 0, $pos)); // переменная
                    $res[$k] = substr($a2, $pos + 3); // значение отдаём как есть
                }
            }

            // делаем замены в тексте
            foreach ($res as $k => $v) {
                $out = str_replace($k, $v, $out);
            }
        }

        // выполним PHP blocks_usephp
        if ($block['blocks_usephp'] == 'php-tpl') {
            $out = tplContent($out, [], true, $block['blocks_id']);
        }

        // прогоним через парсер blocks_parser — пока у нас только simple
        if ($block['blocks_parser'] == 'simple') {

            if (!function_exists('simple')) {
                $parserFile = SYS_DIR . 'lib/simple.php'; // файл парсера

                if (file_exists($parserFile))
                    require_once $parserFile; // подключили файл
            }

            if (function_exists('simple')) $out = simple($out);
        }

        return $out;
    }
}

# end of file
