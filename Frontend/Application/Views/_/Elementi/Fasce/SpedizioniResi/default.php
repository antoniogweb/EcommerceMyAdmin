<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<article class="uk-container uk-container-large">
	<ul uk-grid class="uk-grid uk-child-width-1-1 uk-child-width-1-3@l uk-child-width-1-3@m uk-child-width-1-2@s uk-grid-large uk-margin-top">
        <li>
        	<div class="uk-card" >  	
				<div class="uk-panel">
					<div class="uk-text-left@s uk-text-center">
						<div><?php echo i("__IMMAGINE_FASCE_SPEDIZIONE_RESI_1__", null, 'uk-margin-small-bottom')?></div>
						<h2 class="uk-text-bold uk-margin-small"><?php echo t("Spedizioni gratuite sopra i xxx€");?></h2>
						<h3 class="uk-text-default uk-text-muted uk-margin-remove"><?php echo t("testo_spedizioni_gratuite_sopra_euro")?></h3>
						<?php if (isset($tipiPagina["SPEDIZIONI"])) { ?>
						<a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["SPEDIZIONI"]);?>" uk-link><?php echo gtext("Maggiori informazioni");?></a>
						<?php } ?>
					</div>             
				</div>
			</div>
        </li>

        <li>
        	<div class="uk-card" >  	
				<div class="uk-panel">
					<div class="uk-text-left@s uk-text-center">
						<div><?php echo i("__IMMAGINE_FASCE_SPEDIZIONE_RESI_2__", null, 'uk-margin-small-bottom')?></div>
						<h2 class="uk-text-bold uk-margin-small"><?php echo t("Entro 2-4 gg. lavorativi spediamo il tuo pacco");?></h2>
						<h3 class="uk-text-default uk-text-muted uk-margin-remove"><?php echo t("testo_tempo_spedizione")?></h3>
						<?php if (isset($tipiPagina["SPEDIZIONI"])) { ?>
						<a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["SPEDIZIONI"]);?>" uk-link><?php echo gtext("Maggiori informazioni");?></a>
						<?php } ?>
					</div>             
				</div>
			</div>
        </li>

        <li>
        	<div class="uk-card" >  	
				<div class="uk-panel">
					<div class="uk-text-left@s uk-text-center">
						<div><?php echo i("__IMMAGINE_FASCE_SPEDIZIONE_RESI_3__", null, 'uk-margin-small-bottom')?></div>
						<h2 class="uk-text-bold uk-margin-small"><?php echo t("I nostri resi facili");?></h2>
						<h3 class="uk-text-default uk-text-muted uk-margin-remove"><?php echo t("testo_resi_facili")?></h3>
						<?php if (isset($tipiPagina["RESI"])) { ?>
						<a href="<?php echo $this->baseUrl."/".getUrlAlias($tipiPagina["RESI"]);?>" uk-link><?php echo gtext("Maggiori informazioni");?></a>
						<?php } ?>
					</div>             
				</div>
			</div>
        </li>
    </ul>
    <div class="uk-padding"></div>
</article>
