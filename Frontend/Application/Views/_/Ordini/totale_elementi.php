<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($p["cart"]["gift_card"]) {
	$elementiCarrello = CartelementiModel::getElementiCarrello($p["cart"]["id_cart"]);
?>
	<div class="uk-margin box_elementi_ordine">
		<?php foreach ($elementiCarrello as $elCart) {  ?>
		<div class="uk-padding-small elemento_gift_card" id-cart="<?php echo $p["cart"]["id_cart"];?>">
			<div class="uk-grid uk-grid-small uk-child-width-expand@s uk-position-relative" uk-grid="">
				<div class="uk-first-column uk-width-1-1 uk-width-1-5@m">
					<span class="uk-text-meta"><?php echo gtext("Email");?></span>
				</div>
				<div class="uk-width-expand uk-text-left">
					<span class="uk-text-small"><?php echo $elCart["email"];?></span>
					<a title="<?php echo gtext("Modifica");?>" class="uk-position-right" href="<?php echo $this->baseUrl."/carrello/vedi";?>"><span uk-icon="icon: pencil"></span></a>
				</div>
			</div>
			<div class="uk-grid uk-grid-small uk-child-width-expand@s uk-margin-remove-top uk-margin-remove-bottom" uk-grid="">
				<div class="uk-first-column uk-width-1-1 uk-width-1-5@m">
					<span class="uk-text-meta"><?php echo gtext("Dedica");?></span>
				</div>
				<div class="uk-width-expand uk-text-left dedica_scroll">
					<span class="uk-text-small"><?php echo nl2br($elCart["testo"]);?></span>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
<?php } ?>
