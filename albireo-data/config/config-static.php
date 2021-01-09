<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); 
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// конфигурация для режима генерации статичных страниц

return [
	// каталог хранения готовых статичных страниц
	'staticDir' => BASE_DIR . 'albireo-static' . DIRECTORY_SEPARATOR,	
	'assetsUrl' => 'assets/',
	'layout' => 'main.php',
	'afterCopy' => ['assets'], // после генерации скопировать каталоги
	
	// каталоги, где ещё могут располагаться страницы, кроме DATA_DIR и /pages
	// указывается полный путь
	// 'dirsForPages' => [BASE_DIR . 'my-pages'],
];

# end of file