<?php

/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2021
 *
 * Получение опции из базы sqlite
 * 
 * Использование:
 * $opt = Options\Options::get('top');
 * $opt = Options\Options::get('top', ['def']);
 * echo implode(Options\Options::get('top'));
 * Array (
 *   [0] => 'A'
 *   [1] => 'B'
 * )
 */

namespace Options;

class Options
{
    /** 
     * Получение опции
     * @param string $key - ключ опции
     * @param array $default - если опции нет
     * @return array - возвращает массив опций
     */
    public static function get(string $key, $default = [])
    {
        if (!$key) return $default;

        if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'options')) {
            $pdo = \Pdo\PdoConnect::getInstance();
            $db = $pdo->connect($configDB);
        }

        if (empty($db)) return $default;

        // получить опции
        $rows = \Pdo\PdoQuery::fetchAll(
            $db,
            'SELECT options_value FROM options WHERE options_key = :key',
            [':key' => $key]
        );

        if ($rows) {
            return array_column($rows, 'options_value');
        } else {
            return $default;
        }
    }
}

# end of file
