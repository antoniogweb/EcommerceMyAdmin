<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<article class="uk-container uk-container-expand  uk-margin-large">
	<div class="uk-container uk-container-large uk-padding-large uk-background-muted">
		<div class="uk-container uk-container-small">
			<h1 class="uk-text-emphasis uk-text-large uk-margin-remove uk-text-center uk-margin-left uk-margin-right"><?php echo gtext("I nostri pacchi regalo");?></h1>

			<div class="uk-column-1-1@s uk-column-1-2@m uk-column-1-2@l uk-margin-medium-top uk-margin-left uk-margin-right">
				    <div class="uk-margin uk-text-center uk-text-right@m uk-text-center@s">
						<?php echo t("TESTO_FASCIA_PACCO_REGALO_SX")?>
				    </div>

					<div class="uk-margin uk-text-center uk-text-left@m uk-text-center@s">
						<?php echo t("TESTO_FASCIA_PACCO_REGALO_DX")?>
					</div>
			</div>
			
			<?php if (isset($tipiPagina["PACCO_REGALO"])) { ?>
			<div class="uk-margin-medium-top uk-inline-block" style="display: table;margin:0 auto;">
				<hr class="uk-margin-small">
				<div class="uk-flex uk-flex-center">
				    <div class="uk-padding uk-padding-remove-vertical">
						<a class="uk-text-emphasis" href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["PACCO_REGALO"]);?>"><?php echo gtext("Scopri di più");?></a>
					</div>
				</div>
				<hr class="uk-margin-small">
			</div>
			<?php } ?>
		</div>
	</div>
</article> 
