<?php
if (!defined('EG')) die('Direct access not allowed!');

echo "<li class='$hasChildClass $menuItemClass $subMenuItemClass li_menu_level li_menu_level_".$depth." ".v("menu_class_prefix").$node["node"]["alias"]." $currClass ".$classeCssPersonalizzata."'><a $target class='$subMenuLinkClass $menuLinkClass $notActiveClass ".$currClassLink."' href='".$node["node"]["link_alias"]."'>".$linkText."$inLinkHtmlAfter</a></li>";


