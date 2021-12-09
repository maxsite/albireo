<?php

namespace admin\log;

if (!defined('BASE_DIR')) exit('No direct script access allowed');

class Model
{
    private $db;
    public $connect = true;

    public function __construct()
    {
        if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'log')) {
            $pdo = \Pdo\PdoConnect::getInstance();
            $this->db = $pdo->connect($configDB);
        }

        if (empty($this->db)) {
            $this->connect = false;
            return;
        }

        // удаление таблицы — для тестирования создания новой
        // \Pdo\PdoQuery::dropTable($this->db, 'log'); 

        // проверка существования таблицы log
        if (!\Pdo\PdoQuery::tableExists($this->db, 'log')) {
            // если нет, то создаём новую
            $sql = '
                    CREATE TABLE IF NOT EXISTS log (
                    log_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    log_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                    log_group TEXT DEFAULT "general",
                    log_level TEXT DEFAULT "info",
                    log_message TEXT DEFAULT ""
                );';

            \Pdo\PdoQuery::query($this->db, $sql);
        }
    }

    public function getData()
    {
        // пагинация
        // адрес?page=7 — номер пагинации
        // адрес?page=7&limit=20 — записей на одну страницу пагинации
        $currentUrl = getVal('currentUrl');
        $currentPaged = (int) ($currentUrl['queryData']['page'] ?? 1);
        $limit = (int) ($currentUrl['queryData']['limit'] ?? 20);

        if ($currentPaged < 1) $currentPaged = 1;
        if ($limit < 5) $limit = 20;

        // расчитать OFFSET и другие данные для пагинации
        $pagination = \Pdo\PdoQuery::getPagination($this->db, 'log', $limit, $currentPaged);

        // получаем данные с учетом пагинации
        $rows = \Pdo\PdoQuery::fetchAll(
            $this->db,
            'SELECT * FROM log ORDER BY log_id DESC LIMIT :limit OFFSET :offset',
            [':limit' => $pagination['limit'], ':offset' => $pagination['offset']]
        );

        return [
            'rows' => $rows,
            'currentPaged' => $currentPaged,
            'currentUrl' => $currentUrl,
            'limit' => $limit,
            'pagination' => $pagination,
        ];
    }
}
