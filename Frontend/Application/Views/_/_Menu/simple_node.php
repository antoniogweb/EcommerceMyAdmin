<?php
if (!defined('EG')) die('Direct access not allowed!');

echo "<li class='$currClass'><a $target class='$subMenuLinkClass $menuLinkClass $notActiveClass ".$currClassLink."' href='".$node["node"]["link_alias"]."'>".$linkText."</a></li>";


