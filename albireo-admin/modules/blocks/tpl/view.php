<?php if (!defined('BASE_DIR')) exit('No direct script access allowed'); ?>

<div class="flex t90 flex-wrap-phone mar5-b hover-bg-gray100">
    <div class="pad5-rl w3"><a class="b-inline w100" href="{* SITE_URL*}admin/blocks/preview/{* $blocks_id *}" title="Block preview">{* $blocks_id *}</a></div>
    <div class="pad5-r w20"><a class="b-inline w100" href="{* SITE_URL*}admin/blocks/{* $blocks_id *}" title="Edit block">{* $blocks_key *}</a></div>
    <div class="pad5-r w15">{* $blocks_group1 *}</div>
    <div class="pad5-r w15">{* $blocks_group2 *}</div>
    <div class="pad5-r w15">{* $blocks_group3 *}</div>
    <div class="pad5-r w10">{* $blocks_order *}</div>
    <div class="w20 w100-phone">{* $blocks_mod_local *}</div>
</div>
