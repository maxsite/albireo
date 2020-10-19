<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); 
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// конфигурация для режима генерации статичных страниц

return [
	// каталог хранения готовых статичных страниц
	'staticDir' => BASE_DIR . 'albireo-static' . DIRECTORY_SEPARATOR,	
	'assetsUrl' => '../assets/',
	'layout' => 'main.php',
];

# end of file