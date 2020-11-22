<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

$content = file_get_contents(DATA_DIR . $fileEdit);
$content = htmlspecialchars($content);

// адрес для Ajax на удаление файла 
$urlAjaxDelete = SITE_URL . 'admin/delete-file';
$urlRedirect =  SITE_URL . 'admin/pages';

// адрес страницы на сайте
$previewLink = '';
$pagesInfo = getVal('pagesInfo');
$keyFile = $pagesInfo[DATA_DIR . $fileEdit] ?? '';

if ($keyFile) $previewLink = '<a class="im-external-link-alt t100 t-gray600" href="' . rtrim(SITE_URL . $keyFile['slug'], '/') . '" target="_blank"></a>';

// дальше «резиновая разметка, чтобы textarea занимала всю полезную площадь

?>
<div class="h100vh-min flex flex-column">
    <div class="flex-grow0 flex flex-vcenter pad20-tb bg-yellow250 pad30-rl">
        <h1 class="flex-grow5 h3 mar0"><?= $previewLink ?><?= str_replace('\\', '/', $fileEdit) ?><sup id="flagModified" class="t-gray600"></sup></h1>
        <div class="im-times cursor-pointer hover-t-red600" onclick="deleteFile('<?= $segmentFile ?>')">Delete file</div>
    </div>

    <form id="formEdit" class="pad20-tb pad30-rl flex-grow5 flex flex-column" onsubmit="sendAjax(this); return false;">
        <div class="flex-grow5 h100px bg-blue">
            <textarea class="w100 h100 t-mono lh130" style="tab-size: 4;" name="content" id="content" autofocus><?= $content ?></textarea>
            <input type="hidden" name="_method" value="AJAX">
            <input type="hidden" name="file" value="<?= $segmentFile ?>">
        </div>

        <div class="flex-grow0 pad20-tb flex flex-vcenter">
            <div class="flex-grow0">
                <button class="button button1 im-check pad20-rl" type="submit">Save</button>
            </div>

            <div class="flex-grow5 t-red600 pad20-rl" id="result"><span class="t-gray600">Press <kbd>Ctrl+S</kbd> for save</span></div>
        </div>
    </form>
</div>

<script>
    function sendAjax(f) {
        let xhttp = new XMLHttpRequest();
        let form = new FormData(f);

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML =
                    this.responseText;
                document.getElementById("flagModified").innerHTML = '';
            }
        };

        xhttp.open("POST", "<?= getVal('currentUrl')['urlFull'] ?>", true);
        xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhttp.send(form);
    }

    function deleteFile(file) {
        if (confirm("Delete this file?")) {
            let xhttp = new XMLHttpRequest();
            let form = new FormData();

            form.append("file", file);
            form.append("_method", "AJAX");

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("result").innerHTML =
                        this.responseText;
                    document.location.href = "<?= $urlRedirect ?>";
                }
            };

            xhttp.open("POST", "<?= $urlAjaxDelete ?>", true);
            xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhttp.send(form);
        }
    }

    document.getElementById("content").addEventListener('input',
        function(e) {
            document.getElementById("flagModified").innerHTML = ' changed';
        }
    );

    document.getElementById("content").addEventListener('keydown',
        function(e) {
            if (e.ctrlKey && e.code == 'KeyS') {
                sendAjax(document.getElementById("formEdit"));
                e.preventDefault();
            }
        }
    );
</script>