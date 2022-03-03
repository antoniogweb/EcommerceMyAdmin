<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);
?>
<div class="uk-card uk-card-default uk-card-small regione_<?php echo $p["pages"]["id_regione"];?>" style="box-shadow: none;">
	<div class="uk-card-body">
		<h6 class="uk-margin-remove-bottom uk-text-bold"><?php echo RegioniModel::g(false)->titolo($p["pages"]["id_regione"]);?></h6>
		<h4 class="uk-margin-small-bottom uk-text-bold"><?php echo field($p, "title");?></h4>
		<p class="uk-text-meta uk-margin-remove uk-text-small">
			<?php echo field($p, "indirizzo_localita_evento");?>
			<?php if (field($p, "telefono_contatto_evento")) { ?>
			<br /><?php echo field($p, "telefono_contatto_evento");?>
			<?php } ?>
			<?php if (field($p, "email_contatto_evento")) { ?>
			<br /><a href="mailto:<?php echo field($p, "email_contatto_evento");?>"><?php echo field($p, "email_contatto_evento");?></a>
			<?php } ?>
		</p>
	</div>
</div>
