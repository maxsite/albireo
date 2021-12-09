<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); 

require getConfig('templateLayoutDir') . 'doc-parts/head.php';

?>
<body>
    <section id="myNav" class="w250px h100vh overflow-auto pos-fixed overscroll-behavior-contain bg-teal900 z-index99 hide-tablet w100-tablet">
        <div class="b-hide show-tablet mar20-t mar20-b t90 t-center animation-fade">
            <button class="button button2" onclick="document.getElementById('myNav').classList.toggle('hide-tablet');">✕ Close menu</button>
        </div>

        <header class="t-teal200 bg-teal850 t140 t-center pad5-tb">
			<?php require getConfig('templateLayoutDir') . 'doc-parts/header.php'; ?>
        </header>

        <nav class="pad20-rl">
            <?php require getConfig('templateLayoutDir') . 'doc-parts/nav.php'; ?>
            <?php require getConfig('templateLayoutDir') . 'doc-parts/menu.php'; ?>
        </nav>

        <?php require getConfig('templateLayoutDir') . 'doc-parts/footer.php'; ?>
    </section>

    <section id="myContent" class="w100-phone mar0-tablet" style="margin-left: 250px; max-width: 1100px;">
        <div id="top"></div>
        <div class="b-hide show-tablet mar20-b t90 t-center animation-fade">
            <button class="button button2" onclick="document.getElementById('myNav').classList.toggle('hide-tablet');">☰ Open menu</button>
        </div>

        <article<?= getPageData('articleClass', '', ' class="', '"') ?>>
            <?php
            if ($t = getVal('pageFileContent', ''))
                echo $t;
            else
                require getVal('pageFile');
            ?>
        </article>
    </section>
    
	<?= implode(getKeysPageData('lazy', '[val]')) ?>
</body>
</html>