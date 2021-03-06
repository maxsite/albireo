<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); 
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// конфигурация для обычного вывода страниц

return [
	// url-путь к assets
	'assetsUrl' => SITE_URL . 'assets/',

	// шаблон по умолчанию
	'layout' => 'main.php',

	// каталоги, где ещё могут располагаться страницы, кроме DATA_DIR
	// указывается полный путь
	// 'dirsForPages' => [BASE_DIR . 'my-pages'],

	// отключить кэширование — только для отладки
	// 'noCache' => true,
];

# end of file