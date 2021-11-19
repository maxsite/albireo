<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Примеры работы с SQLite
description: Примеры работы с SQLite в Albireo
slug: sqlite
slug-static: -
sitemap: -
body: class="pad50-rl"

**/

?>

<h1 class="t-center mar50-t">Примеры работы с SQLite в Albireo</h1>
<h6 class="t-center">см. файл <i>albireo-data/pages/sample/sqlite.php</i></h6>
<h6 class="t-center">Cм. описание <a href="https://maxsite.org/page/sqlite-php">https://maxsite.org/page/sqlite-php</a></h6>

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
$rows = Pdo\PdoQuery::fetchAll($db, 'SELECT * FROM myPages WHERE id = :id', [':id' => $id]);

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

// использование \PDO->bindValue() и \PDO->prepare()
$id = 8;

try {
	$sth = $db->prepare('SELECT * FROM myPages WHERE id = :id'); // готовим sql-запрос
	$sth->bindValue(':id', $id, \PDO::PARAM_INT); // привязываем параметры
	$sth->execute(); // выполняем запрос
	$rows = $sth->fetchAll(); // получаем результат
 
	echo Pdo\PdoQuery::outTableRows($rows);// выводим для контроля
} catch (\PDOException $e) {
	echo $e->getMessage();
}


// этот же вариант с помощью fetchAll()
$id = 1;

$rows = Pdo\PdoQuery::fetchAll($db, 
	'SELECT * FROM myPages WHERE id = :id', // sql-запрос
	[':id' => $id], // параметры
	[':id' => \PDO::PARAM_INT] // типы параметров
);

echo Pdo\PdoQuery::outTableRows($rows);

echo '<h2 class="mar50-t">Пример пагинации</h2>';

// пример пагинации
// просто в качестве демо-примера
// адрес?page=7 — номер пагинации

$currentUrl = getVal('currentUrl'); // текущий адрес
$current = (int) ($currentUrl['queryData']['page'] ?? 1); // текущая страница пагинации

if ($current < 1) $current = 1; // исправим, если нужно
		
$limit = 3; // записей на одну страницу пагинации

$pag = Pdo\PdoQuery::getPagination($db, 'myPages', $limit, $current); // массив данных
 
// используем в запросе 
$rows = Pdo\PdoQuery::fetchAll($db, 'SELECT * FROM myPages LIMIT :limit OFFSET :offset', [':limit' => $pag['limit'], ':offset' => $pag['offset']]);

echo Pdo\PdoQuery::outTableRows($rows);

// блок ссылок для пагинации
if ($pag['max'] > 1) {
	echo '<div class="mar30-tb">';

	for ($i = 1; $i <= $pag['max']; $i++) {
		if ($i > 1)
			$queryUrl = $currentUrl['urlFull'] . '?page=' . $i;
		else 
			$queryUrl = $currentUrl['urlFull'];

		if ($i == $current)
			echo '<span class="pad10-rl pad5-tb mar5-r bg-teal600 t-white" style="cursor: default">' . $i . '</span>';		
		else			
			echo '<a class="pad10-rl pad5-tb mar5-r hover-no-underline bg-teal100 hover-bg-teal700 hover-t-teal50" href="' . $queryUrl . '">' . $i . '</a>';
		
	}

	echo '</div>';
}


# end of file
