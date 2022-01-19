<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

/**
 * Меню, которое строится на основе массива файла menu-data.php
 * В нём задаются все пункты и адреса
 * 
 */

// настройки
if (file_exists(CONFIG_DIR . 'menu-config.php'))
	$options = require CONFIG_DIR . 'menu-config.php';
else
	return; // если нет конфигурации меню, выходим

if (file_exists(CONFIG_DIR . 'menu-data.php'))
	$menuEl = require CONFIG_DIR . 'menu-data.php';
else
	return; // нечего выводить

// текущий адрес
$file = getPageData('slug');

if ($file == '/') $file = ':home'; // замена для главной

foreach ($menuEl as $key => $e) {
	// показываем только активную секцию
	$active_section = array_key_exists($file, $e) ? ' open' : '';

	echo '<details' . $active_section . '><summary class="' . $options['header'] . '" style="--summary-marker: \'✚\'; --summary-marker-rotate: 135deg; --summary-marker-time: .6s;">' . $key . '</summary>';
	echo '<ul class="' . $options['list'] . '">';

	foreach ($e as $slug => $name) {
		$link_class = ($file == $slug) ? $options['current'] : $options['elements'];
		$current_add = ($file == $slug) ? $options['currentAdd'] : '';

		if ($slug == ':home') $slug = ''; // замена для главной
		if (defined('GENERATE_STATIC')) $slug .= '.html';

		echo '<li><a class="w100 b-inline pad3-tb pad10-rl ' . $link_class . '" href="' . rtrim(SITE_URL . $slug, '/') . '">' . $name . $current_add . '</a></li>';
	}

	echo '</ul></details>';
}

# end of file
