<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); ?>

<div class="pad30-rl pad10-rl-tablet">
    <form class="mar30-t b-flex flex-vcenter" method="post">
        <input class="form-input w30" type="text" name="key" placeholder="key..." minlength="2" required>
        <input class="form-input w65 mar10-rl" type="text" name="val" placeholder="value...">
        <button class="button button1 pad5-tb pad20-rl im-plus" type="submit">Add a new option</button>
    </form>
</div>