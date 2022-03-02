<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);
?>
<div class="uk-card uk-card-default uk-card-small regione_<?php echo $p["pages"]["id_regione"];?>" style="box-shadow: none;">
	<div class="uk-card-header">
		<h6 class="uk-margin-remove-bottom uk-text-bold"><?php echo RegioniModel::g(false)->titolo($p["pages"]["id_regione"]);?></h6>
		<p class="uk-text-meta uk-margin-remove uk-text-small">
			<?php echo gtext("Email");?>: <?php echo field($p, "email_contatto_evento");?><br />
			<?php echo gtext("Telefono");?>: <?php echo field($p, "telefono_contatto_evento");?><br />
			<?php echo gtext("Indirizzo");?>: <?php echo field($p, "indirizzo_localita_evento");?>
		</p>
	</div>
	<div class="uk-card-body">
		<h4 class="uk-margin-small-bottom uk-text-bold"><?php echo field($p, "title");?></h4>
	</div>
</div>
