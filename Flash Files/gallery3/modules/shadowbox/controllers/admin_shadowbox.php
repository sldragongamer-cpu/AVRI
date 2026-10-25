<?php defined("SYSPATH") or die("No direct script access.");

class Admin_shadowbox_Controller extends Admin_Controller {

  private $newline = false;

  private function save_item_state($statename, $value, $default) {
    $hash = module::get_var("shadowbox", "settings");
    if (isset($hash)):
      $settings = unserialize($hash);
      if (($value) and ($value != $default)):
        $settings[$statename] = $value;
      else:
        if (isset($settings[$statename])):
          unset($settings[$statename]);
        endif;
      endif;
    else:
      $settings = array();
    endif;
    module::set_var("shadowbox", "settings", serialize($settings));
  }

  private function read_item_state($statename, $default) {
    $hash = module::get_var("shadowbox", "settings");
    if (isset($hash)):
      $settings = unserialize($hash);
      if (isset($settings[$statename])):
        return $settings[$statename];
      else:
        return $default;
      endif;
    else:
      return $default;
    endif;
  }

  private function write_line($Handle, $line) {
  	if ($line):
	    if ($this->newline):
	      $prefix = ',';
	    else:
	      $prefix = ' ';
	    endif;

	    $this->newline = true;
	    fwrite($Handle, "    " . $prefix . $line . "\n");
	  endif;
  }

  private function write_setting($Handle, $name, $state, $default, $type) {
    if ($state != $default):
      switch ($type):
        case 1: // bool
          if ($state):
          	$this->write_line($Handle, "$name : true");
          else:
            $this->write_line($Handle, "$name : false");
          endif;
          break;
        case 2: // int
          $this->write_line($Handle,   "$name : $state");
          break;
        case 3: // string
          $this->write_line($Handle,   "$name : '$state'");
          break;
        default:
          $this->write_line($Handle,   "$name : $state");
      endswitch;
    endif;
  }

  public function index() {
    $view = new Admin_View("admin.html");
    $view->content = new View("admin_shadowbox.html");
    $view->content->form = $this->_get_setting_form();
    $view->content->help = $this->get_edit_form_help();
    print $view;
  }

  public function save() {
    access::verify_csrf();

    $form = $this->_get_setting_form();
    if ($form->validate()):

      /* Read Form State ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
      $target         = $form->g_shadowbox_appearance->target->value;
      $viewportPadding= $form->g_shadowbox_appearance->viewportpadding->value;

      $overlayShow    = $form->g_shadowbox_appearance->overlay_show->value;
      $overlayOpacity = $form->g_shadowbox_appearance->overlay_opacity->value;
      $overlayColor   = $form->g_shadowbox_appearance->overlay_color->value;

      $gallery_tag    = $form->g_shadowbox_gallery->gallery_tag->value;
      $continuous     = $form->g_shadowbox_gallery->continuous->value;
      $slideshowDelay = $form->g_shadowbox_gallery->slideshowdelay->value;
      $displayCounter = $form->g_shadowbox_gallery->displaycounter->value;
      $counterType    = $form->g_shadowbox_gallery->countertype->value;
      $counterLimit   = $form->g_shadowbox_gallery->counterlimit->value;
      $displayNav     = $form->g_shadowbox_gallery->displaynav->value;

      $animate        = $form->g_shadowbox_animation->animate->value;
      $animateFade    = $form->g_shadowbox_animation->animatefade->value;
      $animSequence   = $form->g_shadowbox_animation->animsequence->value;

      $enableKeys     = $form->g_shadowbox_behavior->enablekeys->value;
      $close_o_click  = $form->g_shadowbox_behavior->close_o_click->value;

/*      $showCloseBtn   = $form->g_shadowbox_appearance->show_close_btn->value;
      $padding        = $form->g_shadowbox_appearance->padding->value;

      $titlePosition  = $form->g_shadowbox_appearance->title_position->value;

      $transitionIn   = $form->g_shadowbox_animation->transitionin->value;
      $transitionOut  = $form->g_shadowbox_animation->transitionout->value;
      $speedIn        = $form->g_shadowbox_animation->speed_in->value;
      $speedOut       = $form->g_shadowbox_animation->speed_out->value;
      $speedChange    = $form->g_shadowbox_animation->speed_change->value;

      $autoResize     = $form->g_shadowbox_behavior->autoresize->value;
      $centerOnScroll = $form->g_shadowbox_behavior->center_on_scroll->value;
      $closeCnClick   = $form->g_shadowbox_behavior->close_c_click->value;
      $closeOvClick   = $form->g_shadowbox_behavior->close_o_click->value;
      $closeEsc       = $form->g_shadowbox_behavior->close_e_click->value;
      $cyclic         = $form->g_shadowbox_behavior->cyclic->value;
*/
      /* Save Form State ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
      $this->save_item_state("target", $target, "a.g-sb-preview;a.g-fullsize-link");
      $this->save_item_state("viewportPadding", $viewportPadding, "20");

      $this->save_item_state("overlay_hide", !$overlayShow, FALSE);
      $this->save_item_state("overlay_opacity", $overlayOpacity, "0.5");
      $this->save_item_state("overlay_color", $overlayColor, "#000");

      $this->save_item_state("gallery_tag", $gallery_tag, "preview");
      $this->save_item_state("continuous", $continuous, FALSE);
      $this->save_item_state("counterType", $counterType, "default");
      $this->save_item_state("counterLimit", $counterLimit, "10");
      $this->save_item_state("hideCounter", !$displayCounter, FALSE);
      $this->save_item_state("hideNav", !$displayNav, FALSE);
      $this->save_item_state("slideshowDelay", $slideshowDelay, "0");
      
      $this->save_item_state("noAnimate", !$animate, FALSE);
      $this->save_item_state("noAnimateFade", !$animateFade, FALSE);
      $this->save_item_state("animSequence", $animSequence, "sync");

      $this->save_item_state("disableKeys", !$enableKeys, FALSE);
      $this->save_item_state("noCloseOvClick", !$close_o_click, FALSE);
      
      $File = MODPATH . "shadowbox/js/shadowbox-init.js";
      $Handle = fopen($File, 'w');
      fwrite($Handle, "// ******* Auto-generated file. Please do not change. ***\n\n");
      fwrite($Handle, "$(document).ready(function() {\n");
      fwrite($Handle, "  Shadowbox.init({language: 'en', players: ['img', 'html', 'iframe', 'ajax'], autoplayMovies:false});\n");

      $targets = explode(";", $target);
      foreach($targets as $key => $value):
        $this->newline = false;
        fwrite($Handle, "  Shadowbox.setup(\"$value\", {\n");
        $this->write_setting($Handle, "player", "img", "", 3);

        $this->write_setting($Handle, "viewportPadding", $viewportPadding, 20, 2);

        if ($overlayShow):
          $this->write_setting($Handle, "overlayOpacity", $overlayOpacity, 0.5, 2);
          $this->write_setting($Handle, "overlayColor",   $overlayColor,   "#000", 3);
        else:
          $this->write_setting($Handle, "showOverlay", FALSE, TRUE, 1);
        endif;

        // Only for gallery mode
        if ($value != "a.g-fullsize-link"):
          $this->write_setting($Handle, "gallery",    $gallery_tag, "", 3);
          $this->write_setting($Handle, "continuous", $continuous, FALSE, 1);
          $this->write_setting($Handle, "counterType", $counterType, "default", 3);
          $this->write_setting($Handle, "counterLimit", $counterLimit, 10, 2);
          $this->write_setting($Handle, "displayCounter", $displayCounter, TRUE, 1);
          $this->write_setting($Handle, "displayNav", $displayNav, TRUE, 1);
          $this->write_setting($Handle, "slideshowDelay", $slideshowDelay, 0, 2);

          $this->write_setting($Handle, "animate", $animate, TRUE, 1);
          $this->write_setting($Handle, "animateFade", $animateFade, TRUE, 1);
          $this->write_setting($Handle, "animSequence", $animSequence, "skip", 3);

          $this->write_setting($Handle, "enableKeys", $enableKeys, TRUE, 1);
          $this->write_setting($Handle, "modal", !$close_o_click, FALSE, 1);
        endif;
        
        fwrite($Handle, "  });\n\n");
      endforeach;

      fwrite($Handle, "});\n");
      fclose($Handle);

      site_status::clear("shadowbox_config");
      message::success("Settings have been Saved.");
      url::redirect("admin/shadowbox");
    endif;

    $view = new Admin_View("admin.html");
    $view->content = new View("admin_shadowbox.html");
    $view->content->form = $form;
    $view->content->help = $this->get_edit_form_help();
    print $view;
  }

  private function _get_setting_form() {
    $form = new Forge("admin/shadowbox/save", "", "post", array("id" => "g-admin-shadowbox-form"));

    $group = $form->group("g_shadowbox_appearance")->label(t("Appearance Settings"));
    $group->input("target")
      ->label(t("Target (CSS Selector)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a class/id/selector"))
      ->value($this->read_item_state("target", "a.g-sb-preview;a.g-fullsize-link"));
    $group->input("viewportpadding")
      ->label(t("Viewport Padding (Default: 20)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a value"))
      ->value($this->read_item_state("viewportPadding", "20"));

    $group->checkbox("overlay_show")
      ->label(t("Show Overlay (Default: true)"))
      ->checked(!$this->read_item_state("overlay_hide", FALSE));
    $group->dropdown("overlay_opacity")
      ->label(t("Overlay Opacity (default: 0.5)"))
      ->options(array("0.01" => t("0 (transparent)"), "0.1" => "0.1", "0.2" => "0.2", "0.3" => "0.3", "0.4" => "0.4", "0.5" => t("0.5 (default)"), "0.6" => "0.6", "0.7" => "0.7", "0.8" => "0.8", "0.9" => "0.9", "1" => t("1 (opaque)")))
      ->selected($this->read_item_state("overlay_opacity", "0.5"));
    $group->input("overlay_color")
      ->label(t("Overlay Color (default: #000)"))
      ->value($this->read_item_state("overlay_color", "#000"));

    $group = $form->group("g_shadowbox_gallery")->label(t("Gallery Mode Settings"));
    $group->input("gallery_tag")
      ->label(t("Gallery Tag"))
      ->value($this->read_item_state("gallery_tag", "preview"));
    $group->checkbox("continuous")
      ->label(t("Continuous (Default: off)"))
      ->checked($this->read_item_state("continuous", FALSE));
    $group->checkbox("displaycounter")
      ->label(t("Display Counter (Default: on)"))
      ->checked(!$this->read_item_state("hideCounter", FALSE));
    $group->dropdown("countertype")
      ->label(t("Counter Type (default: 1 of 5)"))
      ->options(array("default" => t("1 of 5 (default)"), "skip" => t("1, 2, 3, ...")))
      ->selected($this->read_item_state("counterType", "default"));
    $group->input("counterlimit")
      ->label(t("Counter Limit (Default: 10)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a value"))
      ->value($this->read_item_state("counterLimit", "10"));
    $group->checkbox("displaynav")
      ->label(t("Display Nav (Default: on)"))
      ->checked(!$this->read_item_state("hideNav", FALSE));
    $group->input("slideshowdelay")
      ->label(t("Slideshow Delay (Default: 0)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a value"))
      ->value($this->read_item_state("slideshowDelay", "0"));
      
    $group = $form->group("g_shadowbox_animation")->label(t("Animation Settings"));
    $group->checkbox("animate")
      ->label(t("Animate Transitions (Default: on)"))
      ->checked(!$this->read_item_state("noAnimate", FALSE));
    $group->checkbox("animatefade")
      ->label(t("Animate Fade (Default: on)"))
      ->checked(!$this->read_item_state("noAnimateFade", FALSE));
    $group->dropdown("animsequence")
      ->label(t("Counter Type (default: 1 of 5)"))
      ->options(array("sync" => t("Both simultaneously"), "wh" => t("Width first, then height"), "hw" => t("Height first, then width")))
      ->selected($this->read_item_state("animSequence", "sync"));

    $group = $form->group("g_shadowbox_behavior")->label(t("Behavior Settings"));
    $group->checkbox("enablekeys")
      ->label(t("Enable Keys (Default: on)"))
      ->checked(!$this->read_item_state("disableKeys", FALSE));
    $group->checkbox("close_o_click")
      ->label(t("Close on Click (Default: on)"))
      ->checked(!$this->read_item_state("noCloseOvClick", FALSE));


/*
    $group->dropdown("transitionin")
      ->label(t("Transition In (default: Fade)"))
      ->options(array("default" => t("Fade (default)"), "elastic" => t("Elastic"), "none" => t("None")))
      ->selected($this->read_item_state("transitionIn", "default"));
    $group->dropdown("transitionout")
      ->label(t("Transition Out (default: Fade)"))
      ->options(array("default" => t("Fade (default)"), "elastic" => t("Elastic"), "none" => t("None")))
      ->selected($this->read_item_state("transitionOut", "default"));
    $group->input("speed_in")
      ->label(t("Speed In (default: 300)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a value"))
      ->value($this->read_item_state("speedIn", "300"));
    $group->input("speed_out")
      ->label(t("Speed Out (default: 300)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a value"))
      ->value($this->read_item_state("speedOut", "300"));
    $group->input("speed_change")
      ->label(t("Change Speed (default: 300)"))
      ->rules("required")
      ->error_messages("required", t("You must enter a value"))
      ->value($this->read_item_state("changeSpeed", "300"));

    $group = $form->group("g_shadowbox_behavior")->label(t("Behavior Settings"));
    $group->checkbox("autoresize")
      ->label(t("Auto Resize to Fit (Default: on)"))
      ->checked(!$this->read_item_state("noAutoResize", FALSE));
    $group->checkbox("center_on_scroll")
      ->label(t("Center on Scroll (Default: off)"))
      ->checked($this->read_item_state("CenterOnScroll", FALSE));
    $group->checkbox("close_c_click")
      ->label(t("Close on Content Click (Default: off)"))
      ->checked($this->read_item_state("closeCnClick", FALSE));
    $group->checkbox("close_e_click")
      ->label(t("Close with Esc (Default: on)"))
      ->checked(!$this->read_item_state("noCloseEsc", FALSE));
    $group->checkbox("cyclic")
      ->label(t("Cyclic Navigation (Default: off)"))
      ->checked($this->read_item_state("cyclic", FALSE));
*/
    $form->submit("")->value(t("Save"));
    return $form;
  }

  protected function get_edit_form_help() {
    $help = '<fieldset>';
    $help .= '<legend>Help</legend><ul>';
/*    $help .= '<li><h3>Appearance Settings</h3>
      <p><b>Target (jQuery filter)</b> - specify one or more possible target elements which would be handled by shadowbox
      separated with <b>;</b> delimiter. Default: a.g-sb-preview;a.g-fullsize-link
      <br />Use <b>Show Close Button</b> to toggle Close Button
      <br /><b>Padding</b> - Space between shadowbox wrapper and content
      <br /><b>Overlay Opacity</b> - Opacity of overlay.
      </li>';
    $help .= '<li><h3>Animation Settings</h3>
      <p><b>Speed In</b> - Speed in miliseconds of the zooming-in animation
      <br /><b>Speed Out</b> - Speed in miliseconds of the zooming-out animation
      <br /><b>Change Speed</b> - Speed in miliseconds of the animation when navigating thorugh gallery items
      </li>';
    $help .= '<li><h3>Behavior Settings</h3>
      <p>The following settings should be left on default unless you know what you are doing.
      <p><b>Auto Resize to Fit</b> - Scale images to fit in viewport
      <br /><b>Center on Scroll</b> - Keep image in the center of the browser window when scrolling
      <br /><b>Close on Content Click</b> - Close shadowbox by clicking on the image<br />
      <i>(You may want to leave this off if you display iframed or inline content that containts clickable elements - for example: play buttons for movies, links to other pages)</i>
      <br /><b>Close on Overlay Click</b> - Close shadowbox by clicking on the overlay
      <br /><b>Close with Esc</b> - Close shadowbox when "Escape" key is pressed
      </li>';
    $help .= '</ul></fieldset>';
*/    return $help;
  }
}

?>