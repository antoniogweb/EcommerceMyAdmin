<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php foreach ($messaggi as $m) {
	$ruolo = $m["ruolo"] == "user" ? UsersModel::getName($m["id_admin"]) : "Assistente";
?>
	<?php if ($m["ruolo"] == "user") { ?>
	<div class="uk-width-1-1">
		<div class="uk-flex uk-flex-right">
			<div class="uk-card uk-card-primary uk-card-body uk-padding-small uk-border-rounded uk-width-4-5@s uk-width-1-1">
				<?php echo nl2br($m["messaggio"]);?>
			</div>
		</div>
	</div>
	<?php } else { ?>
	<div class="uk-width-1-1">
		<div class="uk-flex uk-flex-left">
			<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-border-rounded uk-width-4-5@s uk-width-1-1">
				<?php echo htmlentitydecode(nl2br(attivaModuli($m["messaggio"])));?>
			</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>