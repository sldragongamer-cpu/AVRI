<?php defined("SYSPATH") or die("No direct script access.");
$appId = module::get_var("facebook_like", "appId");
$selfURL = url::abs_current(true);
$xfbml = module::get_var("facebook_like", "xfbml");
if (module::get_var("facebook_like", "show_faces")) {
	$show_faces = "true";
	$hite = 65;
} else {
	$show_faces = "false";
	$hite = 35;
}
if (module::get_var("facebook_like", "send")) {
	$send = "true";
} else {
	$send = "false";
}
?>

<? if ($xfbml): ?>

<div class="g-facebook_like">
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=<?= $appId ?>;xfbml=1"></script>
<fb:like href="<?= $selfURL; ?>" send="<?= $send ?>" width="450" show_faces="<?= $show_faces ?>" colorscheme="<?= module::get_var("facebook_like", "colorscheme", "light"); ?>" font=""></fb:like>
<script>
  FB.init({
    appId  : <?= $appId ?>,
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true, // parse XFBML
    oauth  : true // enable OAuth 2.0
  });
</script>
</div>

<? else: ?>

<div class="g-facebook_like">	
<iframe src="http://www.facebook.com/plugins/like.php?app_id=<?= $appId ?>
&amp;href=<?= $selfURL; ?>
&amp;layout=<?= module::get_var("facebook_like", "layout", "standard"); ?>
&amp;send=false
&amp;show_faces=<?= $show_faces ?>
&amp;width=450
&amp;locale=<?= locales::cookie_locale(); ?>
&amp;action=<?= module::get_var("facebook_like", "action", "Like"); ?>
&amp;colorscheme=<?= module::get_var("facebook_like", "colorscheme", "light"); ?>
&amp;height=<?= $hite; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:<?= $hite; ?>px;" allowTransparency="true">
</iframe>
</div>
<? endif ?>

<?php
/**
 * Only show the like button, css and JS if the item is vewable by the guest user 
 * as facebook is a guest user to get the thumb of the item.  If this is a dynamic 
 * album then use the root album to check to see if the guest has permissions.
 */
$guest = user::lookup("1");
$item = "";
if ($theme->item()) {
  $item = $theme->item();
} else {
  $item = ORM::factory("item", 1);
}
if (access::user_can($guest, "view", $item)) {
  $show_like_code = true;
}
?>
