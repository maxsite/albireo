<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); 
/**
 * (c) Albireo Framework, https://maxsite.org/albireo, 2020
 */

// конфигурация для баз данных PDO
// 'alias' => [... config PDO ...]

return [
	'log' => [
		'dsn' => 'sqlite:' . DATA_DIR . 'storage' . DIRECTORY_SEPARATOR . 'log.sqlite',
	],
	
	'options' => [
		'dsn' => 'sqlite:' . DATA_DIR . 'storage' . DIRECTORY_SEPARATOR . 'options.sqlite',
	],
	
	'blocks' => [
		'dsn' => 'sqlite:' . DATA_DIR . 'storage' . DIRECTORY_SEPARATOR . 'blocks.sqlite',
	],

];

# end of file