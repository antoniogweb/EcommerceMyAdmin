<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-card uk-card-default uk-card-small uk-height-1-1 uk-flex uk-flex-column">
<header class="uk-card-header uk-flex uk-flex-middle">
	<div class="uk-grid-small uk-flex-1" uk-grid>
		<div class="uk-width-expand">
			<?php echo gtext("Carrello");?>
		</div>
		<button style="margin-top:-7px;" class="uk-offcanvas-close" type="button" uk-close></button>
	</div>
</header>
<div class="uk-card-body uk-overflow-auto">
	<?php if (count($carrello) > 0) { ?>
	<ul class="uk-list uk-list-divider">
		<?php foreach ($carrello as $p) { 
			$cartUrlAlias = getUrlAlias($p["cart"]["id_page"]);
		?>
		<li class="uk-visible-toggle cart_item_row" rel="<?php echo $p["cart"]["id_cart"];?>">
			<article>
				<div class="uk-grid-small" uk-grid>
					<div class="uk-width-1-4">
						<a class="" href="<?php echo $this->baseUrl."/".$cartUrlAlias;?>">
							<figure class="tm-media-box-wrap"><img src="<?php echo $this->baseUrl."/thumb/carrelloajax/".$p["cart"]["immagine"];?>" alt="<?php echo encodeUrl(field($p, "title"));?>"></figure>
						</a>
					</div>
					<div class="uk-width-expand">
						<a class="uk-text-small" href="<?php echo $this->baseUrl."/".$cartUrlAlias;?>"><?php echo field($p, "title");?></a>
						<?php if ($p["cart"]["attributi"]) { ?>
						<br />
						<?php echo $p["cart"]["attributi"];?>
						<?php } ?>
						
						<div class="uk-margin-xsmall uk-grid-small uk-flex-middle" uk-grid>
							<div class="uk-text-bolder uk-text-small"><?php echo setPriceReverse($p["cart"]["quantity"] * $p["cart"]["price"] * (1+($p["cart"]["iva"]/100)));?> €</div>
							<div class="uk-text-meta uk-text-xsmall"><?php echo $p["cart"]["quantity"];?> × <?php echo setPriceReverse($p["cart"]["price"] * (1+($p["cart"]["iva"]/100)));?> €</div>
						</div>
					</div>
					<div><a class="uk-icon-link uk-text-danger uk-invisible-hover cart_item_delete_link" href="#" uk-icon="icon: close; ratio: .75" uk-tooltip=""></a></div>
				</div>
			</article>
		</li>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<p><?php echo gtext("Il carrello è vuoto")?></p>
	<?php } ?>
</div>
<?php if (count($carrello) > 0) { ?>
	<footer class="uk-card-footer">
		<div class="uk-grid-small" uk-grid>
			<div class="uk-width-expand uk-text-muted uk-h4"><?php echo gtext("Subtotale");?></div>
			<div class="uk-h4 uk-text-bolder"><?php echo getSubTotal(v("prezzi_ivati_in_carrello"));?> €</div>
		</div>
		<div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@m uk-margin-small" uk-grid>
			<div><a class="uk-button uk-button-default uk-margin-small uk-width-1-1" href="<?php echo $this->baseUrl."/carrello/vedi"?>"><?php echo gtext("Carrello");?></a></div>
			<div><a class="uk-button uk-button-primary uk-margin-small uk-width-1-1" href="<?php echo $this->baseUrl."/checkout"?>"><?php echo gtext("Checkout");?></a></div>
		</div>
	</footer>
</div>
<?php } ?>

<div style="display:none;" class="ajax_cart_num_prod"><?php echo $prodInCart;?></div>
