<?php

/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2021
 *
 * PdoQuery
 * 
 * # fetchAll — return all records in array
 * $sql = 'SELECT name, colour, calories FROM mytable WHERE calories < :calories AND colour = :colour';
 * $vars = [':calories' => 150, ':colour' => 'red'];
 * $rows = Pdo\PdoQuery::fetchAll($db, $sql, $vars);
 * foreach($rows as $row) { ... }
 * 
 * # execute with prepare data
 * Pdo\PdoQuery::execute($db, $sql, $vars);
 * 
 * # free sql-query (no prepare data)
 * Pdo\PdoQuery::query($db, 'SELECT ...');
 *  
 * # insert
 * Pdo\PdoQuery::insert($db,  'mytable', ['field1' => $value1, 'field2' => $value2]);
 * 
 * # update
 * Pdo\PdoQuery::update($db, 'mytable', ['password'], ['id' => $id, 'password' => $password], 'id = :id');
 * 
 * # delete
 * Pdo\PdoQuery::delete($db, 'mytable', 'user_id = :id', [':id' => $userId]);
 * 
 * # tableExists
 * if (!Pdo\PdoQuery::tableExists($db, 'mytable')) {
 *	    $sql = 'CREATE TABLE mytable (id INTEGER PRIMARY KEY AUTOINCREMENT, ... );';
 *	    Pdo\PdoQuery::query($db, $sql);
 * }
 * 
 * # drop table
 * Pdo\PdoQuery::dropTable($db, 'mytable');
 * 
 * # All Count record
 * $allCountRecord = Pdo\PdoQuery::countRecord($db, 'mytable');
 * 
 * # пагинация
 *   $current = 1; // номер текущй страницы пагинации начиная с 1
 *   $limit = 10; // записей на одну страницу пагинации
 *   $pag = Pdo\PdoQuery::getPagination($db, 'mytable', $limit, $current);
 *  
 *   Array
 *   (
 *      [limit] => 10 - записей на одну страницу пагинации
 *      [offset] => 70 - смещение offset для sql-запроса
 *      [records] => 85 - всего записей
 *      [current] => 8 - текущая страница пагинации
 *      [max] => 9 - всего страниц пагинации
 *   )
 *  
 *   $rows = Pdo\PdoQuery::fetchAll($db, 'SELECT * FROM mytable LIMIT ' . $limit . ' OFFSET ' . $pag['offset']);
 * 
 */

namespace Pdo;

class PdoQuery
{
    /**
     * Show message
     * 
     * @param string $message
     * @return void
     */
    public static function showMessage(string $message)
    {
        echo '<div class="pad10 bg-red700 t90 t-white t-center"><i class="im-exclamation-triangle"></i>' . $message . '</div>';
    }

    public static function execute(\PDO $db, string $sql, array $bindValue = [])
    {
        try {
            $sth = $db->prepare($sql); //, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY)
            return $sth->execute($bindValue);
        } catch (\PDOException $e) {
            self::showMessage('Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function query(\PDO $db, string $sql)
    {
        try {
            return $db->query($sql);
        } catch (\PDOException $e) {
            self::showMessage('Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function fetchAll(\PDO $db, string $sql, array $bindValue = [])
    {
        try {
            $sth = $db->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $sth->execute($bindValue);
            return $sth->fetchAll();
        } catch (\PDOException $e) {
            self::showMessage('Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function insert(\PDO $db, string $table, array $data)
    {
        $skey =  $sval = '';
        $vars = [];

        foreach ($data as $key => $val) {
            $skey .= $key . ', ';
            $sval .= ':' . $key . ', ';
            $vars[':' . $key] = $val;
        }

        $skey = rtrim(trim($skey), ',');
        $sval = rtrim(trim($sval), ',');

        $sql = 'INSERT INTO ' . $table . ' (' . $skey . ') VALUES (' . $sval . ');';

        try {
            $sth = $db->prepare($sql);
            return $sth->execute($vars);
        } catch (\PDOException $e) {
            self::showMessage('Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function update(\PDO $db, string $table, array $updateField, array $data, $where = '')
    {
        $sfield = '';
        $vars = [];

        foreach ($data as $key => $val) {
            $vars[':' . $key] = $val;
        }

        foreach ($updateField as $field) {
            $sfield .= $field . ' = :' . $field . ', ';
        }

        $sfield = rtrim(trim($sfield), ',');

        if ($where) $where = ' WHERE ' . $where;

        $sql = 'UPDATE ' . $table . ' SET ' . $sfield . $where . ';';

        try {
            $sth = $db->prepare($sql);
            return $sth->execute($vars);
        } catch (\PDOException $e) {
            self::showMessage('Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function delete(\PDO $db, string $table, $where = '', array $bindValue = [])
    {
        if ($where) $where = ' WHERE ' . $where;

        $sql = 'DELETE FROM ' . $table . $where . ';';

        try {
            $sth = $db->prepare($sql);
            return $sth->execute($bindValue);
        } catch (\PDOException $e) {
            self::showMessage('Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function tableExists(\PDO $db, string $table)
    {
        return self::fetchAll($db, 'SELECT name FROM sqlite_master WHERE type="table" AND name="' . $table . '"') ? true : false;
    }

    public static function dropTable(\PDO $db, string $table)
    {
        self::fetchAll($db, 'DROP TABLE IF EXISTS "' . $table . '"');
    }

    public static function countRecord(\PDO $db, string $table, $def = 0)
    {
        $allRows = self::fetchAll($db, 'SELECT COUNT(*) as count FROM "' . $table . '"');

        return isset($allRows[0]['count']) ? $allRows[0]['count'] : $def;
    }

    public static function getPagination(\PDO $db, string $table, int $limit, int $current)
    {
        $records = self::countRecord($db, $table); // всего записей в базе
        $max = ceil($records / $limit); // всего станиц пагинации

        if ($current > $max) $current = $max;

        return [
            'limit' => $limit, // записей на одну страницу пагинации
            'offset' => $current * $limit - $limit, // Offset
            'records' => $records, // всего записей
            'current' => $current, // текущая страница пагинации
            'max' => $max, // всего станиц пагинации
        ];
    }
}

# end of file
