<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2010 Bharat Mediratta
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
class facebook_like_theme_Core {
  static function head($theme) {
    if ($theme->item()) {
		$theme->css("facebook_like.css");
		$item = $theme->item();
		$url = $item->thumb_url(true);
		$appId = module::get_var("facebook_like", "appId");
		$adminId = module::get_var("facebook_like", "adminId");
		$appId = module::get_var("facebook_like", "appId");
		$site_name = module::get_var("facebook_like", "site_name");
		$selfURL = url::abs_current(true);
	  return "\t<meta property=\"og:image\" content=\"$url\"/>
			<meta property=\"og:title\" content=\"$item->title\"/>
			<meta property=\"og:type\" content=\"article\"/>
			<meta property=\"og:url\" content=\"$selfURL\"/>
			<meta property=\"og:site_name\" content=\"$site_name\"/>
			<meta property=\"fb:app_id\" content=\"$appId\"/>
			<meta property=\"fb:admins\" content=\"$adminId\"/>";
	}
  }

  static function sidebar_top($theme) {
  	if (module::get_var("facebook_like", "sidebar_top") && $theme->page_type == "collection"  ||
	  	  module::get_var("facebook_like", "sidebar_top") && $theme->page_type == "item") {
		$view = new View("facebook_like_sidebar.html");
		return $view;
	}
  }
   static function sidebar_bottom($theme) {
    if (module::get_var("facebook_like", "sidebar_bottom") && $theme->page_type == "collection"  ||
	  	  module::get_var("facebook_like", "sidebar_bottom") && $theme->page_type == "item") {
		$view = new View("facebook_like_sidebar.html");
		return $view;	
	}
  }
   static function resize_bottom($theme) {
	if (module::get_var("facebook_like", "resize_bottom")) {
		$view = new View("facebook_like.html");
		return $view;
	}	
  }
   static function resize_top($theme) {
	if (module::get_var("facebook_like", "resize_top")) {
		$view = new View("facebook_like.html");
		return $view;
	}	
  }
   static function album_bottom($theme) {
	if (module::get_var("facebook_like", "album_bottom")) {
		$view = new View("facebook_like.html");
		return $view;
	}	
  }
   static function album_top($theme) {
	if (module::get_var("facebook_like", "album_top")) {
		$view = new View("facebook_like.html");
		return $view;
	}	
  }
}