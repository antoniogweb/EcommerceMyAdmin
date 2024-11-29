<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("attiva_liste_regalo")) { ?>
<li class="<?php if ($attiva == "listeregalo") { ?>uk-active<?php } ?>">
	<a href="<?php echo $this->baseUrl."/liste-regalo/";?>" title="<?php echo gtext("Liste nascita / regalo", false);?>"><?php echo gtext("Liste nascita / regalo");?></a>
</li>
<?php } ?>
