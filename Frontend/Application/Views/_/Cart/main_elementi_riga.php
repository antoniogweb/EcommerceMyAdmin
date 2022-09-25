<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($p["cart"]["gift_card"]) {
	$elementiCarrello = CartelementiModel::getElementiCarrello($p["cart"]["id_cart"]);
	foreach ($elementiCarrello as $elCart) {  ?>
	<div class="form_elemento_gift_card uk-margin-top uk-padding-small uk-padding-remove-right uk-padding-remove-left elemento_gift_card" id-cart="<?php echo $p["cart"]["id_cart"];?>">
		<div class="uk-grid uk-grid-small uk-child-width-expand@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?>" uk-grid="">
			<div class="uk-first-column uk-width-1-1 uk-width-1-5@m">
			</div>
			<div class="uk-width-expand uk-text-left">
				<div class="uk-margin">
					<label class="uk-form-label"><?php echo gtext("Email");?></label>
					<div class="uk-form-controls">
						<div class="uk-grid uk-grid-column-small" uk-grid>
							<div class="uk-margin uk-margin-remove-bottom uk-width-1-1 uk-width-2-3@m">
								<div class="uk-inline">
									<span class="uk-form-icon" uk-icon="icon: mail"></span>
									<?php
									$classeInput = (CartelementiModel::evidenzia($pageView) && !CartelementiModel::isOkField($elCart["id_cart_elemento"], "email")) ? "uk-form-danger" : "";
									echo Html_Form::input("email",$elCart["email"],"$classeInput uk-input uk-form-width-large");?>
								</div>
							</div>
							<div class="uk-width-1-1 uk-width-1-3@m uk-text-meta uk-margin-remove-top">
								<?php echo gtext("Scrivi qui l'email della persona a cui vuoi regalare la gift card");?>
							</div>
						</div>
					</div>
				</div>
				<div class="uk-margin">
					<label class="uk-form-label"><?php echo gtext("Testo della dedica e firma");?></label>
					<div class="uk-form-controls">
						<?php
						$classeInput = (CartelementiModel::evidenzia($pageView) && !CartelementiModel::isOkField($elCart["id_cart_elemento"], "testo")) ? "uk-form-danger" : "";
						echo Html_Form::textarea("testo",$elCart["testo"],"$classeInput uk-textarea");?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>
