<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); ?>

<div x-data="{show: true}" x-show.transition.out.opacity.duration.3000ms="show" class="mar30 pad10 bg-green600 t-green100 lh100 rounded"><i class="im-info-circle"></i>{{ $message }} <i @click="show = false" class="im-times-circle icon0 b-right t-green200 hover-t-white cursor-pointer"></i></div>