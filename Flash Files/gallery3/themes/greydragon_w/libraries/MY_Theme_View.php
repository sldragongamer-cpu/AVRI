<?
class Theme_View extends Theme_View_Core {

  protected $photonav_position;
  protected $logopath;
  protected $copyright = null;

  protected function ensurevalue($value, $default) {
    if ((!isset($value)) or ($value == "")):
      return $default;
    else:
      return $value;
    endif;
  }

  protected function ensureoptionsvalue($key, $default) {
    return ($this->ensurevalue(module::get_var("th_greydragon", $key), $default));
  }

  public function load_sessioninfo() {
    $this->logopath = $this->ensureoptionsvalue("logo_path", url::file("lib/images/logo.png"));
    $this->copyright = ($this->ensureoptionsvalue("copyright", null));
    $this->photonav_position = module::get_var("th_greydragon", "photonav_position", "top");
  }

  public function add_paginator($position) {
    if (($this->photonav_position == "both") or ($this->photonav_position == $position)):
      return ($this->paginator());
    else:
      return "";
    endif;
  }

}

?>