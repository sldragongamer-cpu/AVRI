<?php defined("SYSPATH") or die("No direct script access.");

class shadowbox_event_Core {

  static function admin_menu($menu, $theme) {
    $menu
      ->get("settings_menu")
      ->append(Menu::factory("link")
               ->id("shadowbox")
               ->label(t("Shadowbox"))
               ->url(url::site("admin/shadowbox")));
  }
}
