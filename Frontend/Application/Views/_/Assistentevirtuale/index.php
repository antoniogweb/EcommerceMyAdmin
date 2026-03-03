<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-flex uk-flex-column uk-height-viewport uk-background-default uk-card uk-card-default uk-card-body uk-padding-small@s uk-padding">
	<div class="uk-flex-1 uk-overflow-auto uk-margin-small-bottom">
		<div class="uk-grid-small" uk-grid>
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
						<div class="uk-card uk-card-secondary uk-card-body uk-padding-small uk-border-rounded uk-width-4-5@s uk-width-1-1">
							<?php echo htmlentitydecode(nl2br(attivaModuli($m["messaggio"])));?>
						</div>
					</div>
				</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
	
	<div class="uk-card uk-card-default uk-card-body uk-padding-small uk-margin-remove-top">
		<div class="uk-flex uk-flex-middle uk-grid-small" uk-grid>
			<div class="uk-width-expand">
				<input class="request_message uk-input" type="text" placeholder="<?php echo gtext("Scrivi un messaggio...");?>">
			</div>
			<div class="uk-width-auto">
				<button class="send_request_to_va uk-button uk-button-primary" type="button"><?php echo gtext("Invia");?></button>
			</div>
		</div>
	</div>
</div>
