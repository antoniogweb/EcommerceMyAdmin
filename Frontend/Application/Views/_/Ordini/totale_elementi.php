<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($p["cart"]["gift_card"]) {
	$elementiCarrello = CartelementiModel::getElementiCarrello($p["cart"]["id_cart"]);
?>
	<div class="uk-margin box_elementi_ordine">
		<?php foreach ($elementiCarrello as $elCart) {  ?>
		<div class="uk-padding-small elemento_gift_card uk-padding-remove-left uk-padding-remove-right" id-cart="<?php echo $p["cart"]["id_cart"];?>">
			<?php if (false) { ?>
			<div class="uk-margin">
				<label class="uk-form-label"><?php echo gtext("Email della persona a cui vuoi regalare la gift card");?></label>
				<div class="uk-form-controls">
					<div class="uk-grid uk-grid-column-small" uk-grid>
						<div class="uk-margin uk-margin-remove-bottom uk-width-1-1">
							<div class="uk-inline">
								<span class="uk-form-icon" uk-icon="icon: mail"></span>
								<?php echo Html_Form::input("email",$elCart["email"],"uk-input uk-form-width-large");?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Testo della dedica e firma");?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::textarea("testo",$elCart["testo"],"uk-textarea ",null);?>
				</div>
			</div>
			<?php } ?>
			<?php if (true) { ?>
			<div class="uk-grid uk-grid-small uk-child-width-expand@s uk-position-relative" uk-grid="">
				<div class="uk-first-column uk-width-1-1 uk-width-2-5@m">
					<span class="uk-text-meta"><?php echo gtext("Invia alla e-mail");?></span>
				</div>
				<div class="uk-width-expand uk-text-left uk-margin-remove-top">
					<span class="uk-text-small"><?php echo $elCart["email"] ? $elCart["email"] : gtext("-- non definita --");?></span>
					<a title="<?php echo gtext("Modifica");?>" class="uk-position-right" href="<?php echo $this->baseUrl."/carrello/vedi";?>"><span uk-icon="icon: pencil"></span></a>
				</div>
			</div>
			<div class="uk-grid uk-grid-small uk-child-width-expand@s uk-margin-remove-top uk-margin-remove-bottom" uk-grid="">
				<div class="uk-first-column uk-width-1-1 uk-width-2-5@m">
					<span class="uk-text-meta"><?php echo gtext("Dedica e firma");?></span>
				</div>
				<div class="uk-width-expand uk-text-left uk-margin-remove-top dedica_scroll">
					<span class="uk-text-small"><?php echo nl2br($elCart["testo"]);?></span>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
<?php } ?>
