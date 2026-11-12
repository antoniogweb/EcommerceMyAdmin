<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($p["cart"]["gift_card"]) {
	$elementiCarrello = CartelementiModel::getElementiCarrello($p["cart"]["id_cart"]);
	foreach ($elementiCarrello as $elCart) {  ?>
	<?php
	$erroreEmailGiftCard = CartelementiModel::evidenzia($pageView) && !CartelementiModel::isOkField($elCart["id_cart_elemento"], "email");
	$erroreTestoGiftCard = CartelementiModel::evidenzia($pageView) && !CartelementiModel::isOkField($elCart["id_cart_elemento"], "testo");
	if (!isset($campiGiftCardInErrore))
		$campiGiftCardInErrore = array();
	if ($erroreEmailGiftCard && !in_array(gtextPlain("Email"), $campiGiftCardInErrore, true))
		$campiGiftCardInErrore[] = gtextPlain("Email");
	if ($erroreTestoGiftCard && !in_array(gtextPlain("Testo della dedica e firma"), $campiGiftCardInErrore, true))
		$campiGiftCardInErrore[] = gtextPlain("Testo della dedica e firma");
	?>
	<div class="form_elemento_gift_card uk-margin-top uk-padding-small uk-padding-remove-right uk-padding-remove-left elemento_gift_card" id-cart="<?php echo $p["cart"]["id_cart"];?>">
		<div class="uk-grid uk-grid-small uk-child-width-expand@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?>" uk-grid="">
			<div class="uk-first-column uk-width-1-1 uk-width-1-5@m">
			</div>
			<div class="uk-width-expand uk-text-left">
				<div class="uk-margin">
				<label class="uk-form-label" for="gift_card_email_<?php echo (int)$elCart["id_cart_elemento"];?>"><?php echo gtextPlain("Email");?> <span aria-hidden="true">*</span></label>
					<div class="uk-form-controls">
						<div class="uk-grid uk-grid-column-small" uk-grid>
							<div class="uk-margin uk-margin-remove-bottom uk-width-1-1 uk-width-2-3@m">
								<div class="uk-inline">
									<span class="uk-form-icon" aria-hidden="true" uk-icon="icon: mail"></span>
									<?php
									$classeInput = $erroreEmailGiftCard ? "uk-form-danger" : "";
									$attributiEmail = "autocomplete='email' aria-required='true'";
									if ($classeInput)
										$attributiEmail .= " aria-invalid='true'";
									echo Html_Form::input("email",$elCart["email"],"$classeInput uk-input uk-form-width-large","gift_card_email_".(int)$elCart["id_cart_elemento"],$attributiEmail,"email");?>
								</div>
							</div>
							<div class="uk-width-1-1 uk-width-1-3@m uk-text-meta uk-margin-remove-top">
								<?php echo gtextPlain("Scrivi qui l'email della persona a cui vuoi regalare la gift card");?>
							</div>
						</div>
					</div>
				</div>
				<div class="uk-margin">
					<label class="uk-form-label" for="gift_card_testo_<?php echo (int)$elCart["id_cart_elemento"];?>"><?php echo gtextPlain("Testo della dedica e firma");?> <span aria-hidden="true">*</span></label>
					<div class="uk-form-controls">
						<?php
						$classeInput = $erroreTestoGiftCard ? "uk-form-danger" : "";
						$attributiTesto = "aria-required='true'";
						if ($classeInput)
							$attributiTesto .= " aria-invalid='true'";
						echo Html_Form::textarea("testo",$elCart["testo"],"$classeInput uk-textarea","gift_card_testo_".(int)$elCart["id_cart_elemento"],$attributiTesto);?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>
