<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// конфигурация для режима генерации статичных страниц

return [
	// каталог хранения готовых статичных страниц
	'staticDir' => BASE_DIR . 'albireo-static' . DIRECTORY_SEPARATOR,

	// шаблон сайта    
	'template' => 'default', // имя (каталог) шаблона внутри albireo-templates

	// настройки шаблона
	'layoutDir' => 'layout', // каталог layout внутри текущего шаблона
	'assets' => 'assets', // url-путь к assets относительно текущего шаблона

	// файл вывода по умолчанию в layout
	'layout' => 'main.php',

	// после генерации скопировать каталоги (исходный относительно корня => куда относительно конечного)
	'afterCopy' => [
		'albireo-templates/default/assets' => 'assets',
	],

];

# end of file
