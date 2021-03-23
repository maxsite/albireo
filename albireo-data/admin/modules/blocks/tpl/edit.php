<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

function makeOption(array $data, string $key, array $el)
{
    $current = $data[$key] ?? '';
    $out = '';

    foreach ($el as $value => $name) {
        $sel = ($current == $value) ? ' selected' : '';
        $out .= '<option value="' . $value . '"' . $sel . '>' . $name . '</option>';
    }

    return $out;
}

?>

<div x-data="{textData: '', btn: ''}">
    <div x-html="textData" x-show.transition.duration.1000ms="textData" @click="textData = ''" class="bg-green200 pad10 t90 rounded10 pos-fixed" style="right: 30px; top: 60px;"></div>

    <form x-ref="form" @submit="
        var f = new FormData($refs.form);
        if (btn == 'delete') {
            f.append('btn', btn);
            btn = '';
        }
        f.append('_method', 'AJAX');
        fetch('<?= SITE_URL ?>admin/blocks/{{ $blocks_id }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: f
        })
        .then(response => response.text())
        .then(response => {textData = response; if (textData == 'redirect') window.location.href = '<?= SITE_URL ?>admin/blocks'});
        return false;
        " class="mar30-t" method="post">

        <input type="hidden" name="_method" value="AJAX">

        <div class="t-bold t90 mar5-b">Name</div>

        <div class="flex">
            <input class="w50 form-input mar10-r" type="text" name="blocks_key" placeholder="name..." minlength="2" value="{* $blocks_key *}" required>

            <div class="w15 mar10-r">
                <select class="form-input w100" name="blocks_parser">
                    {{ makeOption($DATA, 'blocks_parser', ['' => 'No parser', 'simple' => 'Simple']) }}
                </select>
            </div>

            <div class="w15 mar10-r">
                <select class="form-input w100" name="blocks_usephp">
                    {{ makeOption($DATA, 'blocks_usephp', ['' => 'No PHP', 'php-tpl' => 'Php-Tpl']) }}
                </select>
            </div>

            <input class="w10 mar10-r form-input" type="text" name="blocks_order" placeholder="order..." value="{* $blocks_order *}">

            <button class="t90 button button1 pad5-tb im-check" type="submit">Save</button>
        </div>

        <details class="mar20-t b-inline">
            <summary class="hover-t-gray700 t90">Additional</summary>

            <div class="mar10-t bg-gray100 pad20">
                <div class="t-bold t90 mar5-b">Groups</div>

                <div class="flex flex-wrap-phone">
                    <div class="w20 mar5-r">
                        <input class="w100 form-input" type="text" name="blocks_group1" placeholder="group1..." value="{* $blocks_group1 *}">
                    </div>

                    <div class="w20 mar5-r">
                        <input class="w100 form-input" type="text" name="blocks_group2" placeholder="group2..." value="{* $blocks_group2 *}">
                    </div>

                    <div class="w20 mar5-r">
                        <input class="w100 form-input" type="text" name="blocks_group3" placeholder="group3..." value="{* $blocks_group3 *}">
                    </div>

                    <div class="w20 mar5-r">
                        <input class="w100 form-input" type="text" name="blocks_group4" placeholder="group4..." value="{* $blocks_group4 *}">
                    </div>

                    <div class="w20 mar5-r">
                        <input class="w100 form-input" type="text" name="blocks_group5" placeholder="group5..." value="{* $blocks_group5 *}">
                    </div>

                </div>

                <div class="t-bold t90 mar5-b mar20-t">Info</div>
                <textarea class="w100 form-input t90" name="blocks_info" rows="2">{* $blocks_info *}</textarea>
            </div>
        </details>


        <div class="mar20-t t-bold t90 mar5-b">Content</div>
        <textarea class="w100 form-input t-mono t90" name="blocks_content" rows="15">{* $blocks_content *}</textarea>

        <div class="mar10-t t-bold t90 mar5-b">Variables</div>
        <textarea class="w100 form-input t-mono t90" name="blocks_vars" rows="4">{* $blocks_vars *}</textarea>

        <div class="mar10-t t-bold t90 mar5-b">Start block</div>
        <textarea class="w100 form-input t-mono t90" name="blocks_start">{* $blocks_start *}</textarea>

        <div class="mar10-t t-bold t90 mar5-b">End block</div>
        <textarea class="w100 form-input t-mono t90" name="blocks_end">{* $blocks_end *}</textarea>

        <div class="mar20-tb">
            <button class="button button1 pad5-tb pad20-rl im-check" type="submit">Save</button>

            <button @click="if (confirm('Delete this block?')) {btn = 'delete'; return true;}" class="b-right button button2 pad5-tb pad20-rl im-times" type="submit">Delete</button>
        </div>
    </form>
</div>