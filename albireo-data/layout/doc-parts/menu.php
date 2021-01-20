<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**
 * Меню, которое строится автоматом на основе параметров страниц
 * 
 * menu[title]: О проекте
 * menu[group]: General
 * menu[order]: 1
 * 
 */

// настройки
if (file_exists(CONFIG_DIR . 'menu-config.php'))
	$options = require CONFIG_DIR . 'menu-config.php';
else
	return; // если нет конфигурации меню, выходим

$menuEl = []; // массив данных меню

// проходимся по всем страницам
foreach (getVal('pagesInfo') as $file => $pageData) {

	// исключаем всю админ-панель
	if (strpos($file, DATA_DIR . 'admin' . DIRECTORY_SEPARATOR) !== false) continue;

	// берём те, у которых есть параметр «menu»
	if ($m = getKeysPageData('menu', '', $pageData)) {

		// если не указан menu[title] не выводим в меню
		if (!$title = $m['title'] ?? '') continue;

		$group =  $m['group'] ?? 'General';
		$slug = $m['slug'] ?? $pageData['slug'];
		$order = $m['order'] ?? '10';

		// добавляем order для последующей сортировки
		$menuEl[$group][$order . '@' . $slug] = $title;
	}
}

// теперь сортировка для учета order@
$e = [];

foreach ($menuEl as $k => $v) {
	ksort($v, SORT_NATURAL);
	$v2 = [];

	foreach ($v as $a => $b) {
		$pos = strpos($a, '@');

		if ($pos !== false) {
			$a = substr($a, $pos + 1);
			$v2[$a] = $b;
		}
	}

	$e[$k] = $v2;
}

$menuEl = $e;

// если нет элементов меню, выходим
if (!$menuEl) return;

// сортировка групп как указано в groupOrder
$groupOrder = $options['groupOrder'] ?? false;

if ($groupOrder) {
    $mt = [];
    
    foreach($groupOrder as $g) {
        if (isset($menuEl[$g])) $mt[$g] = $menuEl[$g];    
    }
    
    $menuEl = array_merge($mt, $menuEl);
}

// текущий адрес
$file = getPageData('slug');

foreach ($menuEl as $key => $e) {
	$active_section = array_key_exists($file, $e) ? ' open' : '';

	echo '<details' . $active_section . '><summary class="' . $options['header'] . '" style="--summary-marker: \'➕\'; --summary-marker-rotate: 135deg; --summary-marker-time: .6s;">' . $key . '</summary>';

	echo '<ul class="' . $options['list'] . '">';

	foreach ($e as $slug => $name) {

		$link_class = ($file == $slug) ? $options['current'] : $options['elements'];
		$current_add = ($file == $slug) ? $options['currentAdd'] : '';

		if (defined('GENERATE_STATIC')) $slug .= '.html';
		
		echo '<li><a class="w100 b-inline pad3-tb pad10-rl ' . $link_class . '" href="' . rtrim(SITE_URL . $slug, '/') . '">' . $name . $current_add . '</a></li>';
	}

	echo '</ul></details>';
}

# end of file
