<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Примеры работы с SQLite
description: Примеры работы с SQLite в Albireo
slug: sqlite
slug-static: -
sitemap: -

**/

?>

<h1 class="t-center mar50-t">Примеры работы с SQLite в Albireo</h1>
<h6 class="t-center">см. файл <i>albireo-data/pages/sample/sqlite.php</i></h6>

<?php

// получение экземпляра Pdo
$pdo = Pdo\PdoConnect::getInstance();

// получение соединения PDO
// указывается параметры базы в виде ключа dsn
// в SQLite нужно только имя файла
// если файла нет, то он будет автоматически создан
$db = $pdo->connect([
        'dsn' => 'sqlite:' . DATA_DIR . 'storage' . DIRECTORY_SEPARATOR . 'test.sqlite'
    ]);

if (empty($db)) {
    echo '<div class="t-red600 t-center mar10">Ошибка соединения с БД</div>';
    return; // выходим, поскольку нет возможности работы с базой
} else {
    echo '<div class="t-green600 t-center mar20-b">Соединение с БД установлено</div>';
}

// можно полностью удалить таблицу, чтобы создать её заново с нуля
// Pdo\PdoQuery::query($db, "DROP TABLE IF EXISTS myPages;");
// или так
Pdo\PdoQuery::dropTable($db, 'myPages');

// создадим новую таблицу
// для этого выполним обычный sql-запрос
Pdo\PdoQuery::query($db, "
CREATE TABLE IF NOT EXISTS myPages (
   id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
   title TEXT NOT NULL,
   content TEXT NOT NULL DEFAULT '',
   status TEXT NOT NULL DEFAULT 'publish',
   date TEXT NOT NULL DEFAULT '',
   level INTEGER NOT NULL DEFAULT 0
);
");

// очистим таблицу
// Pdo\PdoQuery::query($db, "DELETE FROM myPages;");

// или так
// Pdo\PdoQuery::delete($db, 'myPages');


// добавим новые записи через обычный SQL
Pdo\PdoQuery::query($db, "
INSERT INTO myPages (title, status, content, date) VALUES
   ('Первая запись', 'publish', 'Текст первой записи', datetime('now', 'localtime')),
   ('Вторая запись', 'draft', 'Текст второй записи', datetime('now', 'localtime')),
   ('Третья запись', 'publish', 'Текст третьей записи', datetime('now', 'localtime')),
   ('Четвёртая запись', 'publish', 'Текст четвёртой записи', datetime('now', 'localtime')),
   ('Пятая запись', 'draft', 'Текст пятой записи', datetime('now', 'localtime')),
   ('Шестая запись', 'publish', 'Текст шестой записи', datetime('now', 'localtime')),
   ('Седьмая запись', 'publish', 'Текст седьмой записи', datetime('now', 'localtime')),
   ('Восьмая запись', 'draft', 'Текст восьмой записи', datetime('now', 'localtime'))
;");

// или так — в «чистом sql-виде» через PDO
// если значение — строка, то нужно добавить кавычки
// INSERT INTO myPages (title, status, content, date, level) VALUES ('Девятая запись', 'publish', 'Текст девятой записи', datetime('now', 'localtime'), 1);
Pdo\PdoQuery::insertSql($db,  'myPages', [
    'title' => "'Девятая запись'",
    'status' => "'publish'",
    'content' => "'Текст девятой записи'",
    'date' => "datetime('now', 'localtime')",
    'level' => 1,
]);

// или так — через PDO prepare
// INSERT INTO myPages (title, status, content, date) VALUES (:title, :status, :content, :date);
Pdo\PdoQuery::insert($db,  'myPages', [
    'title' => "Десятая запись",
    'status' => "publish",
    'content' => "Текст десятой записи",
    'date' => date("Y-m-d H:i:s"),
]);

// обновим данные 10-й записи (указывается в id)
Pdo\PdoQuery::update($db, 'myPages',
    [ // какие поля нужно обновить
		'title',
		'content',
		'level'
	],
    [ // данные для этих полей
        'id' => 10, // используется во WHERE
        'title' => '10-я запись',
        'content' => 'Текст 10-й записи',
        'level' => 2,
    ],
    'id = :id' // уловие WHERE
);


// сделаем выборку всех данных
$rows = Pdo\PdoQuery::fetchAll($db, 'SELECT * FROM myPages');

// выведем на экран
echo Pdo\PdoQuery::outTableRows($rows);

echo '<p class="mar20-tb">Всего записей: ' . Pdo\PdoQuery::countRecord($db, 'myPages') . '</p>';


// сделаем выборку по условиям через PDO prepare
$id = 7;
$rows = Pdo\PdoQuery::fetchAll($db, 'SELECT * FROM myPages WHERE id = :id', ['id' => $id]);

// выведем на экран результат
echo Pdo\PdoQuery::outTableRows($rows);


// Другая выборка через обычный SQL с PDO prepare (но здесь не используем)
$rows = Pdo\PdoQuery::fetchAll($db, 'SELECT * FROM myPages WHERE id > 7');

// выведем на экран результат
echo '<br>';
echo Pdo\PdoQuery::outTableRows($rows);


// Другая выборка через обычный SQL без PDO prepare
$rows = Pdo\PdoQuery::query($db, 'SELECT id, title FROM myPages WHERE id > 2 AND id < 5');

// выведем на экран результат в цикле
echo '<br>';

foreach($rows as $row) {
	echo '<p>' . $row['id'] . ' : ' . htmlspecialchars($row['title']) . '</p>';

	// pr($row); // для отладки
}


# end of file
