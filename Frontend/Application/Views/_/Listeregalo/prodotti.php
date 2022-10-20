<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!User::$isMobile) { ?>
<div class="uk-visible@m">
	<div class="uk-text-meta uk-grid-small uk-child-width-1-1 uk-child-width-1-5 uk-flex-middle uk-grid" uk-grid="">
		<div class="uk-first-column">
			<?php echo gtext("Prodotto");?>
		</div>
		<div class="uk-width-expand">
			<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-center uk-grid" uk-grid="">
				<div class="uk-first-column">
					<?php echo gtext("Descrizione");?>
				</div>
				<div>
					<?php echo gtext("Quantità desiderata");?>
				</div>
				<div>
					<?php echo gtext("Regalati");?>
				</div>
				<div>
					<?php echo gtext("Rimasti");?>
				</div>
				<div>
					<?php echo gtext("Cancella");?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>
