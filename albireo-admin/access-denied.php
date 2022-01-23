<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');
/**

title: Access denied
slug: admin/access-denied
slug-static: -
layout: admin/core/_landing.php

**/

?>
<div class="b-flex flex-jc-center mar100-t">
    <div class="b-flex flex-vcenter">
        <div class="t200 t-white t-bold pad30 bg-orange600"><i class="im-exclamation-triangle icon0"></i></div>
        <div class="pad20">
            <div class="t200 t-gray600">Access denied</div>
            <?php
                if (isset($_SESSION['onAccessDeniedMessage']))
                    echo '<div class="mar10-tb t90 t-gray500">' . $_SESSION['onAccessDeniedMessage'] . '</div>';
            ?>
            <div class="flex">
                <a class="im-home" href="<?= SITE_URL ?>">Home →</a>
                <a class="im-sign-out-alt t-gray600" href="<?= SITE_URL ?>admin/logout">Logout</a>
            </div>
        </div>
    </div>
</div>
