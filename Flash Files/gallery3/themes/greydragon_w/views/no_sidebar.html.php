<?php defined("SYSPATH") or die("No direct script access.") ?>
<ul class="g-message-block">
  <li class="g-warning">
    <? if (block_manager::get_active("site_sidebar")): ?>
    <?= t("Active sidebar blocks have no content.") ?>
    <? else: ?>
    <?= t("No active sidebar blocks.") ?>
    <? endif ?>
    <br/>
    <a href="<?= url::site("admin/sidebar") ?>"><?= t("Configure blocks") ?></a>
  </li>
</ul>
