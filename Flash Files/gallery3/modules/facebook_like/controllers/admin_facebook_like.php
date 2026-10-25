<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2009 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class Admin_Facebook_like_Controller extends Admin_Controller {
  public function index() {
    print $this->_get_view();
  }

  public function handler() {
    access::verify_csrf();

    $form = $this->_get_form();
    if ($form->validate()) {
      module::set_var(
        "facebook_like", "appId", $form->fb->appId->value);
      module::set_var(
        "facebook_like", "adminId", $form->fb->adminId->value);
      module::set_var(
        "facebook_like", "site_name", $form->fb->site_name->value);
      module::set_var(
        "facebook_like", "xfbml", $form->fb->xfbml->value, true);
      module::set_var(
        "facebook_like", "show_faces", $form->fb->show_faces->value, true);
      module::set_var(
        "facebook_like", "send", $form->fb->send->value, true);
      module::set_var(
        "facebook_like", "colorscheme", $form->fb->colorscheme->value);
      module::set_var(
        "facebook_like", "action", $form->fb->action->value);
      module::set_var(
        "facebook_like", "layout", $form->fb->layout->value);
      module::set_var(
        "facebook_like", "resize_top", $form->fb->resize_top->value, true);
      module::set_var(
        "facebook_like", "resize_bottom", $form->fb->resize_bottom->value, true);
      module::set_var(
        "facebook_like", "sidebar_top", $form->fb->sidebar_top->value, true);
      module::set_var(
        "facebook_like", "sidebar_bottom", $form->fb->sidebar_bottom->value, true);
      module::set_var(
        "facebook_like", "album_top", $form->fb->album_top->value, true);
		module::set_var(
        "facebook_like", "album_bottom", $form->fb->album_bottom->value, true);
        
      message::success(t("Your settings have been saved."));
      url::redirect("admin/facebook_like");
    }
	
    print $this->_get_view($form);
  }

  private function _get_view($form=null) {
    $v = new Admin_View("admin.html");
    $v->content = new View("admin_facebook_like.html");
    $v->content->form = empty($form) ? $this->_get_form() : $form;
    return $v;
  }

  private function _get_form() {

    $form = new Forge("admin/facebook_like/handler", "", "post", array("id" => "g-admin-form"));
    
    $group = $form->group("fb")->label(t("Settings for the button"));
	$group->input("appId")->label(t('Enter the appId from <a href="http://developers.facebook.com/setup/" target="_blank">FaceBook Create an App</a>.  You must get your *own* appID.  If you see the number "123456789012345" it is only a demo.<br />
										Note: www.example.com/ is different than example.com/'))
		->value(module::get_var("facebook_like", "appId", "123456789012345"))
		->rules("valid_numeric");
	$group->input("adminId")->label(t('Enter *your* Facebook *numeric* ID, which you can get as the "Admin" field in Stage 2 of <a href="http://developers.facebook.com/docs/reference/plugins/like/" target="_blank">FaceBook Like Button Config</a>.  If you see the number "123456789012345" it is only a demo.<br />'))
		->value(module::get_var("facebook_like", "adminId", "123456789012345"))
		->rules("valid_numeric");
	$group->input("site_name")->label(t('Enter the site name you want to show on Facebook.'))
		->value(module::get_var("facebook_like", "site_name", "Gallery"));
 	$group->checkbox("xfbml")->label(t("Use the XFBML version rather than the iFrame version (allows the Send button and better dialogs)."))
		->checked(module::get_var("facebook_like", "xfbml"));
	$group->checkbox("show_faces")->label(t("Display profile photos of the Facebook friends who 'Like' below the Like button (standard layout only)."))
		->checked(module::get_var("facebook_like", "show_faces"));
	$group->checkbox("send")->label(t("Include a Send button with the Like button. This only works with the XFBML version."))
		->checked(module::get_var("facebook_like", "send"));
   $group->dropdown("colorscheme")
      ->label(t("Color Scheme"))
      ->options(array("light" => t("Light"),
                      "dark" => t("Dark")))
      ->selected(module::get_var("facebook_like", "colorscheme"));
    $group->dropdown("action")
      ->label(t("Verb to display"))
      ->options(array("like" => t("Like"),
                      "recommend" => t("Recommend")))
      ->selected(module::get_var("facebook_like", "action"));
	$group->dropdown("layout")
      ->label(t("Layout style. Determines the size and amount of social context next to the button"))
      ->options(array("standard" => t("Standard."),
                      "button_count" => t("Button count.")))
      ->selected(module::get_var("facebook_like", "layout"));
	$group->checkbox("resize_top")->label(t("Show on top of resize photo."))
		->checked(module::get_var("facebook_like", "resize_top"));
	$group->checkbox("resize_bottom")->label(t("Show on bottom of resize photo."))
		->checked(module::get_var("facebook_like", "resize_bottom"));
	$group->checkbox("sidebar_top")->label(t("Show on top of sidebar."))
		->checked(module::get_var("facebook_like", "sidebar_top"));
	$group->checkbox("sidebar_bottom")->label(t("Show on bottom of sidebar."))
		->checked(module::get_var("facebook_like", "sidebar_bottom"));
	$group->checkbox("album_top")->label(t("Show on top of album thumbs. (This block does not show on dynamic pages.)"))
		->checked(module::get_var("facebook_like", "album_top"));
	$group->checkbox("album_bottom")->label(t("Show on bottom of album thumbs. (This block does not show on dynamic pages.)"))
		->checked(module::get_var("facebook_like", "album_bottom"));	

	$form->submit("submit")->value(t("Save"));
    return $form;
  }
}