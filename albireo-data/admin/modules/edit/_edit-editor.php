<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

$content = file_get_contents(BASE_DIR . $fileEdit);
$content = htmlspecialchars($content);

// адрес для Ajax на удаление файла 
$urlAjaxDelete = SITE_URL . 'admin/delete-file';
$urlRedirect =  SITE_URL . 'admin/pages';

// адрес страницы на сайте
$previewLink = '';
$pagesInfo = getVal('pagesInfo');
$keyFile = $pagesInfo[BASE_DIR . $fileEdit] ?? '';

if ($keyFile) $previewLink = '<a class="im-external-link-alt t100 t-gray600" href="' . rtrim(SITE_URL . $keyFile['slug'], '/') . '" target="_blank"></a>';

// кнопки к редактору
$buttons = '';

$configAdmin = getConfigAdmin();


    $editorButton = $configAdmin['editorButton'] ?? [];
    $editorButtonMode = $configAdmin['editorButtonMode'] ?? 'click';
    
    if ($editorButtonMode == 'hover') {
        $addMode1 = '@mouseover="open = true" @mouseout="open = false" @click="open = true"';
        $addMode2 = '@mouseover="open = true" @mouseout="open = false"';
    } else {
        $addMode1 = '@click="open = true"';
        $addMode2 = '';
    }
    
    // используется Alpine.js
    foreach ($editorButton as $name => $group) {
        $buttons .= '<div x-data="{open: false}" class="pos-relative b-inline">';
        $buttons .= '<button ' . $addMode1 . ' :class="{\'bg-teal500\': open}" class="button mar5-r hover-bg-teal500">' . $name . '</button>';
        $buttons .= '<div x-show="open" ' . $addMode2 . ' @click.away="open = false" @click="open = false" class="animation-fade bordered pos-absolute w100px-min z-index1 bg-white b-shadow-var" x-cloak>';

        foreach ($group as $button) {
            $title = $button[3] ?? '';

            if ($title) $title = ' title="' . htmlspecialchars($title) . '"';

            if ($button[0] == '-')
                $buttons .= '<hr class="mar5-tb bor-dotted-t bor1">';
            else
                $buttons .= '<div class="pad10-rl hover-bg-blue100 cursor-pointer" onClick="addText(\'' . $button[1] . '\', \'' . $button[2] . '\');"' . $title . '>' . $button[0] . '</div>';
        }

        $buttons .= '</div></div>';
    }

$readOnly = verifyLogin(['admin-change-files']) ? '' : ' <sup class="t-red600">read only</sup>';


// дальше «резиновая разметка, чтобы textarea занимала всю полезную площадь

?>

<div class="h100vh-min flex flex-column">
    <div class="flex-grow0 flex flex-vcenter pad20-tb bg-yellow250 pad30-rl mar10-b">
        <h1 class="flex-grow5 h3 mar0"><?= $previewLink ?><?= str_replace('\\', '/', $fileEdit) ?><sup id="flagModified" class="t-gray600"></sup><?= $readOnly ?></h1>
        <div class="im-times cursor-pointer hover-t-red600" onclick="deleteFile('<?= $segmentFile ?>')">Delete file</div>
    </div>

    <div class="pad30-rl t90"><?= $buttons ?></div>

    <form id="formEdit" class="pad20-b pad10-t pad30-rl flex-grow5 flex flex-column" onsubmit="sendAjax(this); return false;">
        <div class="flex-grow5 h100px">

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
    var modified = false;

    function sendAjax(f) {
        let xhttp = new XMLHttpRequest();
        let form = new FormData(f);

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("result").innerHTML =
                    this.responseText;
                document.getElementById("flagModified").innerHTML = "";
                modified = false;
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
                    document.getElementById("result").innerHTML = this.responseText;
                    document.location.href = "<?= $urlRedirect ?>";
                }
            };

            xhttp.open("POST", "<?= $urlAjaxDelete ?>", true);
            xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhttp.send(form);
        }
    }

    document.getElementById("content").addEventListener("input",
        function(e) {
            if (!modified) {
                document.getElementById("flagModified").innerHTML = " changed";
                modified = true;
            }
        }
    );

    document.getElementById("content").addEventListener("keydown",
        function(e) {
            if (e.ctrlKey && e.code == "KeyS") {
                sendAjax(document.getElementById("formEdit"));
                e.preventDefault();
            }
        }
    );

    // при добавлении не работает ctrl+z, хз как исправить...
    function addText(t, t2) {
        var editor = document.getElementById("content");

        if (document.selection) {
            editor.focus();
            sel = document.selection.createRange();
            sel.text = t + sel.text + t2;
            editor.focus();
        } else if (editor.selectionStart || editor.selectionStart == "0") {
            var startPos = editor.selectionStart;
            var endPos = editor.selectionEnd;
            var cursorPos = endPos;
            var scrollTop = editor.scrollTop;
            if (startPos != endPos) {
                editor.value = editor.value.substring(0, startPos) +
                    t +
                    editor.value.substring(startPos, endPos) +
                    t2 +
                    editor.value.substring(endPos, editor.value.length);
                cursorPos = startPos + t.length
            } else {
                editor.value = editor.value.substring(0, startPos) +
                    t +
                    t2 +
                    editor.value.substring(endPos, editor.value.length);
                cursorPos = startPos + t.length;
            }
            editor.focus();
            editor.selectionStart = cursorPos;
            editor.selectionEnd = cursorPos;
            editor.scrollTop = scrollTop;
        } else {
            editor.value += t + t2;
        }

        document.getElementById("flagModified").innerHTML = " changed";
        modified = true;
    }
</script>