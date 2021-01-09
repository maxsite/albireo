<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: demo access2
description: 
slug: demo-access2
parser: simple
slug-static: -
init-file: admin/core/_functions.php

 **/
 
// пример доступа к части контента страницы только по разрешению
 
// проверка логина
$user = getUser();
$access = ($user and checkUserAccess($user, ['secret content', 'pages']));

$userNik = $user['nik'] ?? '';
if ($userNik) $userNik = ' ' . htmlspecialchars($userNik);

?>

h1 Hello<?= $userNik ?>!

_ Free access.

<?php

if (!$access) {
    echo '<p class="t-red">You do not have permission to view this content</p>';

    if (!$user) echo '<p><a href="' . SITE_URL . 'admin/login">Login</a></p>';

    return;
}

?>

_ Content full.

_ <a href="<?= SITE_URL ?>admin/logout">Logout →</a>