<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); ?>

<div x-data="vInit({* $options_id *}, '{* $options_key *}', '{* $options_value *}')" x-show.transition.out.opacity.duration.3000ms="show" class="flex pad3 t90 mar10-b">
    <div class="w40 mar10-r">
        <input x-model="key" x-spread="saveKbd" class="form-input w100" type="text" placeholder="key...">
        <div class="mar10-t">
            <button x-spread="saveBtn" class="button t-gray500 hover-t-white bg-gray50 hover-bg-teal600 pad3-tb pad10-rl">Save</button>

            <button x-spread="deleteBtn" class="button t-gray500 hover-t-white bg-gray50 hover-bg-red500 pad3-tb pad10-rl">Delete</button>

            <span x-text="message" class="t-gray600 mar10-l"></span>
        </div>
    </div>

    <div class="w60 pad10-r"><textarea x-model="val" class="form-input w100 t-mono" rows="3" placeholder="value..."></textarea></div>
</div>