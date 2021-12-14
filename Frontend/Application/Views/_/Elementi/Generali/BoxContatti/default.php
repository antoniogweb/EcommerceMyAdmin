<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-background-muted uk-padding uk-margin">

	<h2 class="uk-text-bold"><?php echo gtext("Contattaci")?></h2>
	
	<?php if (v("email_aziendale")) { ?>
	<div class="uk-margin-bottom">
		<a uk-link href="mailto:<?php echo v("email_aziendale");?>"><span class="uk-margin-small-right" uk-icon="icon: mail"></span> <?php echo v("email_aziendale");?></a>
	</div>
	<?php } ?>
	
	<?php if (v("telefono_aziendale")) { ?>
	<div class="uk-margin-bottom">
		<a uk-link href="tel:<?php echo v("telefono_aziendale");?>"><span class="uk-margin-small-right" uk-icon="icon: phone"></span> <?php echo v("telefono_aziendale");?></a>
	</div>
	<?php } ?>
	
	<?php if (v("telefono_aziendale_2")) { ?>
	<div class="uk-margin-bottom">
		<a uk-link href="mailto:<?php echo v("telefono_aziendale_2");?>"><span class="uk-margin-small-right" uk-icon="icon: receiver"></span> <?php echo v("telefono_aziendale_2");?></a>
	</div>
	<?php } ?>
	
	<div class="uk-margin-bottom">
		<span class="uk-margin-small-right" uk-icon="icon: location"></span><?php echo v("indirizzo_aziendale");?>
	</div>

	<?php include(tpf("/Elementi/social_list.php"));?>
	
</div> 
