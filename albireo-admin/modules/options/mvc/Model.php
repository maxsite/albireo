<?php

namespace admin\modules\options\mvc;

if (!defined('BASE_DIR')) exit('No direct script access allowed');

class Model
{
    private $db;
    public $connect = true;

    public function __construct()
    {
        if ($configDB = getConfigFile(CONFIG_DIR . 'dbase.php', 'options')) {
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
        if (!\Pdo\PdoQuery::tableExists($this->db, 'options')) {
            // если нет, то создаём новую
            $sql = '
                    CREATE TABLE IF NOT EXISTS options (
                    options_id INTEGER PRIMARY KEY AUTOINCREMENT,
                    options_key TEXT DEFAULT "",
                    options_value TEXT DEFAULT ""
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

        // рассчитать OFFSET и другие данные для пагинации
        $pagination = \Pdo\PdoQuery::getPagination($this->db, 'options', $limit, $currentPaged);

        // получаем данные с учетом пагинации
        $rows = \Pdo\PdoQuery::fetchAll(
            $this->db,
            'SELECT * FROM options ORDER BY options_id DESC LIMIT :limit OFFSET :offset',
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
        $value = $_POST['val'] ?? '';

        $errors = [];
        $message = 'Error!';

        if ($key === '') $errors[] = 'Incorrect key';
        if (mb_strlen($key) < 2) $errors[] = 'Key must be more than 2 characters';

        if (!$errors) {
            // разрешение на изменение файлов
            if (verifyLogin(['admin-change-files'])) {
                \Pdo\PdoQuery::insert($this->db, 'options', ['options_key' => $key, 'options_value' => $value]);
            }

            $message = 'Ok! The new option (' . htmlspecialchars($key) . ') is created';
        }

        return ['errors' => $errors, 'message' => $message];
    }

    public function update($id, $key, $value)
    {
        $errors = [];

        $id = (int) $id;

        if ($id < 1) $errors[] = 'Error: Incorrect data (id)';
        if ($key === '') $errors[] = 'Incorrect key';
        if (mb_strlen($key) < 2) $errors[] = 'Key must be more than 2 characters';

        $message = 'Ok!';

        if (!$errors) {
            // разрешение на изменение файлов
            if (verifyLogin(['admin-change-files'])) {
                $res = \Pdo\PdoQuery::update($this->db, 'options', ['options_key', 'options_value'], ['id' => $id, 'options_key' => $key, 'options_value' => $value], 'options_id = :id');

                if (!$res) $message = 'Error update';
            }
        } else {
            $message = 'Error! ' . implode(' | ', $errors);
        }

        return $message;
    }

    public function delete($id)
    {
        $errors = [];

        $id = (int) $id;

        if ($id < 1) $errors[] = 'Error: Incorrect data (id)';

        $message = 'Ok!'; // важно — по этому тексту скрывается блок после удаления

        if (!$errors) {
            // разрешение на изменение файлов
            if (verifyLogin(['admin-change-files'])) {
                $res = \Pdo\PdoQuery::delete($this->db, 'options', 'options_id = :id', [':id' => $id]);

                if (!$res) $message = 'Error delete';
            }
        } else {
            $message = 'Error! ' . implode(' | ', $errors);
        }

        return $message;
    }
}
