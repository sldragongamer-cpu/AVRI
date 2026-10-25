<?php defined("SYSPATH") or die("No direct script access.");

class shadowbox_theme_Core {
  static function head($theme) {
    return
        $theme->css("shadowbox.css")
      . $theme->script("shadowbox.js")
      . $theme->script("shadowbox-init.js");
  }
}
