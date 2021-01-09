<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: demo access1 
description: 
slug: demo-access1
slug-static: -
parser: simple
init-file: admin/core/_functions.php

**/

// пример доступа к странице по логину и разрешению

// проверка доступа к странице
verifyLoginRedirect(['pages', 'admin'], 'You do not have permission to access to<br>' . getVal('currentUrl')['urlFull']);

$user = getUser();
$userNik = $user['nik'] ?? '';
if ($userNik) $userNik = ' ' . htmlspecialchars($userNik);

?>

h1 Hello <?= $userNik ?>!

_ <a href="<?= SITE_URL ?>admin/logout">Logout →</a>
