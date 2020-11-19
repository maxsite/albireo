<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

$menuEl = []; // массив данных меню

// проходимся по всем страницам
foreach (getVal('pagesInfo') as $file => $pageData) {

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

// pr($menuEl);

$idUL = 1;

// текущий адрес
$file = getPageData('slug');

if ($file == '/') $file = ':home'; // замена для главной

foreach ($menuEl as $key => $e) {
    echo '<h5 class="t-small-caps bor1 bor-dotted-b bor-gray400 pad10-b mar0-t mar5-b t-teal100">' . $key . '</h5>';

    echo '<ul class="list-unstyled t90 hover-no-underline mar20-b">';

    $out = '';

    foreach ($e as $slug => $name) {
        $link_class = ($file == $slug) ? 't-teal100 bg-teal700 hover-t-teal100 rounded3' : 'rounded3 hover-bg-teal750 t-teal100 hover-t-teal100';

        $current_add = ($file == $slug) ? '<span class="b-inline b-right"> ●</span>' : '';
        $root_url = SITE_URL;

        if ($slug == ':home') $slug = ''; // замена для главной

        $out .= '<li><a class="w100 b-inline pad3-tb pad10-rl ' . $link_class . '" href="' . $root_url . $slug . '">' . $name . $current_add . '</a></li>';
    }

    echo $out;
    echo '</ul>';

    $idUL++;
}

# end of file
