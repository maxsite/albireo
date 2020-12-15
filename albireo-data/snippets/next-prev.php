<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); 

// данные сниппета доступны в переменной $data

if (!is_array($data)) return; // должен быть массив

// в первом элементе slug на предыдущую страницу
// во втором — следующая

$pagesInfo = getVal('pagesInfo');

// title находим автоматом
$prevTitle = $prevURL = $nextTitle = $nextURL = '';

if (isset($data[0])) {
    foreach ($pagesInfo as $page) {
        if ($page['slug'] == $data[0]) {
            $prevTitle = isset($page['title']) ? htmlspecialchars($page['title']) : htmlspecialchars($page['slug']);
            $prevURL = SITE_URL . $page['slug'] . STATIC_EXT;
            
            break;
        }
    }
}

if (isset($data[1])) {
    foreach ($pagesInfo as $page) {
        if ($page['slug'] == $data[1]) {
            $nextTitle = isset($page['title']) ? htmlspecialchars($page['title']) : htmlspecialchars($page['slug']);
            $nextURL = SITE_URL . $page['slug'] . STATIC_EXT;
            
            break;
        }
    }
}
?>
<div class="flex flex-wrap mar40-tb">
    <div class="w5col">
        <?php if ($prevURL) : ?>
            <a href="<?= $prevURL ?>">← <?= $prevTitle ?></a>
        <?php endif ?>
    </div>

    <div class="w5col t-right">
        <?php if ($nextURL) : ?>
            <a href="<?= $nextURL ?>"><?= $nextTitle ?> →</a>
        <?php endif ?>
    </div>
</div>
