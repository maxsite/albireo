<?php

namespace admin\modules\blocks\mvc;

if (!defined('BASE_DIR')) exit('No direct script access allowed');

class Model
{
    private $db;
    public $connect = true;

    public function __construct()
    {
        if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'blocks')) {
            $pdo = \Pdo\PdoConnect::getInstance();
            $this->db = $pdo->connect($configDB);
        }

        if (empty($this->db)) {
            $this->connect = false;
            return;
        }

        // удаление таблицы — для тестирования создания новой
        // \Pdo\PdoQuery::dropTable($this->db, 'blocks'); 

        // проверка существования таблицы
        if (!\Pdo\PdoQuery::tableExists($this->db, 'blocks')) {
            // если нет, то создаём новую
            $sql = '
                    CREATE TABLE IF NOT EXISTS blocks (
                        blocks_id INTEGER PRIMARY KEY AUTOINCREMENT,
                        blocks_key TEXT NOT NULL,
                        blocks_content TEXT DEFAULT "",
                        blocks_start TEXT DEFAULT "",
                        blocks_end TEXT DEFAULT "",
                        blocks_vars TEXT DEFAULT "",
                        blocks_parser TEXT DEFAULT "",
                        blocks_usephp TEXT DEFAULT "",
                        blocks_info TEXT DEFAULT "",
                        blocks_order TEXT DEFAULT "",
                        blocks_group1 TEXT DEFAULT "",
                        blocks_group2 TEXT DEFAULT "",
                        blocks_group3 TEXT DEFAULT "",
                        blocks_group4 TEXT DEFAULT "",
                        blocks_group5 TEXT DEFAULT "",
                        blocks_mod DATETIME DEFAULT CURRENT_TIMESTAMP
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
        $limit = (int) ($currentUrl['queryData']['limit'] ?? 10);

        if ($currentPaged < 1) $currentPaged = 1;
        if ($limit < 5) $limit = 10;

        // расчитать OFFSET и другие данные для пагинации
        $pagination = \Pdo\PdoQuery::getPagination($this->db, 'blocks', $limit, $currentPaged);

        $timezone = getConfigAdmin('timezone');
        if (!$timezone) $timezone = '+2 hours';

        // получаем данные с учетом пагинации
        $rows = \Pdo\PdoQuery::fetchAll(
            $this->db,
            "SELECT *, strftime('%Y-%m-%d %H:%M:%S', blocks_mod, '" . $timezone . "') AS blocks_mod_local FROM blocks ORDER BY blocks_mod DESC LIMIT :limit OFFSET :offset",
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

    public function addNew()
    {
        // нужные данные в POST
        $key = $_POST['key'] ?? '';

        $errors = [];
        $message = 'Error!';

        if ($key === '') $errors[] = 'Incorrect key';
        if (mb_strlen($key) < 2) $errors[] = 'Key must be more than 2 characters';

        if (!$errors) {
            // разрешение на изменение файлов
            if (verifyLogin(['admin-change-files'])) {
                \Pdo\PdoQuery::insert($this->db, 'blocks', ['blocks_key' => $key]);
            }

            $message = 'Ok! The new block (' . htmlspecialchars($key) . ') is created';
        }

        return $message . implode(' | ', $errors);
    }

    public function getDataOne()
    {
        $currentUrl = getVal('currentUrl');
        $id = (int) str_replace('admin/blocks/', '', $currentUrl['url']);

        if ($id > 0) {
            $rows = \Pdo\PdoQuery::fetchAll(
                $this->db,
                'SELECT * FROM blocks WHERE blocks_id = :id',
                [':id' => $id]
            );

            if ($rows)
                return $rows[0];
            else
                return false;
        } else {
            return false;
        }
    }

    public function update($blocks_id, $data)
    {
        $errors = [];

        $blocks_id = (int) $blocks_id;
        $blocks_key = $_POST['blocks_key'] ?? '';

        if ($blocks_id < 1) $errors[] = 'Error: Incorrect data (id)';
        if ($blocks_key === '') $errors[] = 'Incorrect key';
        if (mb_strlen($blocks_key) < 2) $errors[] = 'Key must be more than 2 characters';

        $blocks_parser = $_POST['blocks_parser'] ?? '';
        $blocks_usephp = $_POST['blocks_usephp'] ?? '';
        $blocks_order = $_POST['blocks_order'] ?? '';
        $blocks_info = $_POST['blocks_info'] ?? '';
        $blocks_group1 = $_POST['blocks_group1'] ?? '';
        $blocks_group2 = $_POST['blocks_group2'] ?? '';
        $blocks_group3 = $_POST['blocks_group3'] ?? '';
        $blocks_group4 = $_POST['blocks_group4'] ?? '';
        $blocks_group5 = $_POST['blocks_group5'] ?? '';
        $blocks_content = $_POST['blocks_content'] ?? '';
        $blocks_vars = $_POST['blocks_vars'] ?? '';
        $blocks_start = $_POST['blocks_start'] ?? '';
        $blocks_end = $_POST['blocks_end'] ?? '';
        $blocks_mod = gmstrftime('%Y-%m-%d %H:%M:%S'); // время послденей модификации блока по Гринвичу

        $message = 'Ok! Block #' . $blocks_id . ' updated';

        if (!$errors) {
            // разрешение на изменение файлов
            if (verifyLogin(['admin-change-files'])) {
                $res = \Pdo\PdoQuery::update(
                    $this->db,
                    'blocks',
                    [
                        'blocks_key',
                        'blocks_parser',
                        'blocks_usephp',
                        'blocks_order',
                        'blocks_info',
                        'blocks_group1',
                        'blocks_group2',
                        'blocks_group3',
                        'blocks_group4',
                        'blocks_group5',
                        'blocks_content',
                        'blocks_vars',
                        'blocks_start',
                        'blocks_end',
                        'blocks_mod',
                    ],

                    [
                        'blocks_id' => $blocks_id,
                        'blocks_key' => $blocks_key,
                        'blocks_parser' => $blocks_parser,
                        'blocks_usephp' => $blocks_usephp,
                        'blocks_order' => $blocks_order,
                        'blocks_info' => $blocks_info,
                        'blocks_group1' => $blocks_group1,
                        'blocks_group2' => $blocks_group2,
                        'blocks_group3' => $blocks_group3,
                        'blocks_group4' => $blocks_group4,
                        'blocks_group5' => $blocks_group5,
                        'blocks_content' => $blocks_content,
                        'blocks_vars' => $blocks_vars,
                        'blocks_start' => $blocks_start,
                        'blocks_end' => $blocks_end,
                        'blocks_mod' => $blocks_mod,
                    ],

                    'blocks_id = :blocks_id'
                );

                if (!$res) $message = 'Error update';
            }
        } else {
            $message = 'Error! ' . implode(' | ', $errors);
        }

        return $message;
    }

    public function delete($id)
    {
        $errors = '';

        $id = (int) $id;

        if ($id < 1) $errors = 'Error: Incorrect data (id)';

        $message = 'Ok! Block #' . $id . ' deleted';

        if (!$errors) {
            // разрешение на изменение файлов
            if (verifyLogin(['admin-change-files'])) {
                $res = \Pdo\PdoQuery::delete($this->db, 'blocks', 'blocks_id = :id', [':id' => $id]);

                if (!$res) $message = 'Error delete';
            }
        } else {
            $message = 'Error! ' . $errors;
        }

        return $message;
    }
}
