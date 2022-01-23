<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Albireo Admin
description:
slug: admin
slug-static: -
layout: admin/core/_layout.php
parser: simple
admin-menu[title]: <i class="im-info-circle"></i>Information
admin-menu[group]: General
admin-menu[order]: 1

**/

verifyLoginRedirect(['admin'], 'You do not have permission to access the admin panel!');

$user = getUser();

$pagesInfo = getVal('pagesInfo');

$pagesInfo = array_filter($pagesInfo, function($val, $key){
    return (strpos($key, ADMIN_DIR) === FALSE);
}, ARRAY_FILTER_USE_BOTH);

// определим последние изменённые файлы
$lastFile = [];

foreach ($pagesInfo as $file => $info) {
	$t = $t1 = filemtime($file);

	$t .= '-' . $file;

	$lastFile[$t] = $info;
	$lastFile[$t]['__file_name'] = str_replace(BASE_DIR, '', $file);
	$lastFile[$t]['__file_time'] = $t1;
}

krsort($lastFile);
$lastFile = array_slice($lastFile, 0, 20); // оставим часть

$userNik = $user['nik'] ?? '';

if ($userNik) $userNik = ' ' . htmlspecialchars($userNik);

?>

<div class="bg-yellow250 pad30-rl pad20-tb flex flex-vcenter flex-wrap-tablet">
    div
        h1(mar0) Welcome to Albireo!
        __(t-gray600 t90) Free open-source PHP framework
    /div

    __(im-user) <?= $userNik ?>

</div>

<div class="pad30-rl">

    div(flex mar20-t)
        div
            <a class="mar20-r im-book t-nowrap" href="https://maxsite.org/albireo" target="_blank">Albireo  (documentation)</a>
            <a class="mar20-r im-star t-nowrap" href="https://github.com/maxsite/albireo" target="_blank">Albireo on GitHub</a>
            <a class="mar20-r im-comment-dots t-nowrap" href="https://github.com/maxsite/albireo/discussions" target="_blank">Discussions</a>
            <a class="mar20-r im-link t-nowrap" href="https://maxsite.org/" target="_blank">MaxSite.org</a>
        /div

        __(t-right t-nowrap) <a class="mar20-r im-donate t-orange550 animation-bounce animation-infinite animation-slow b-inline" href="https://max-3000.com/page/donation" target="_blank" title="Donation"></a>
    /div

    h2 Information

    ul(no-bullet no-margin)
    * Total Pages: <?= count($pagesInfo) ?>

    /ul

    h2 Last files

    ul(no-bullet no-margin mar30-b)
    <?php

        foreach ($lastFile as $info) {
            $editUrl = SITE_URL . 'admin/edit/' . encodeURL64($info['__file_name']);
            $pageUrl = rtrim(SITE_URL . $info['slug'], '/');
            $title = $info['title'] ?? '! no title';

            $mod = date('Y-m-d H:i', $info['__file_time']);

            echo '<li><a class="im-external-link-alt" href="' . $pageUrl . '"></a> <a href="' . $editUrl . '">' . str_replace('\\', '/', $info['__file_name']) . '</a> → <span class="t-bold">' . htmlspecialchars($title) . '</span> <span class="t80 t-bold t-gray500">' . $mod . '</span></li>';
        }

    ?>
    /ul
</div>
