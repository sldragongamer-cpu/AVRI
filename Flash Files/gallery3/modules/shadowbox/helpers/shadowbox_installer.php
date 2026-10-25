<?php defined("SYSPATH") or die("No direct script access.");

class shadowbox_installer {
  static function activate() {
    shadowbox::check_config();
  }

  static function deactivate() {
    site_status::clear("shadowbox_config");
  }
}
?>