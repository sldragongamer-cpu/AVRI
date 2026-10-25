<?php defined("SYSPATH") or die("No direct script access.");

class shadowbox_Core {

  static function check_config() {
    $hash = module::get_var("shadowbox", "settings");
    if (isset($hash)):
      site_status::warning(
        t("Shadowbox is not quite ready! Please visit <a href=\"%url\">admin page</a>, verify and save settings before first use.",
          array("url" => html::mark_clean(url::site("admin/shadowbox")))),
        "shadowbox_config");
    else:
      site_status::clear("shadowbox_config");
    endif;    
  }
}
?>
