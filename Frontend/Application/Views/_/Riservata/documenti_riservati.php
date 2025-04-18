<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Documenti riservati") => "",
);

$titoloPagina = gtext("Documenti riservati");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "documenti-riservati";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($documenti) > 0) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column">
				<?php echo gtext("Data caricamento");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Documento");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Tipo");?>
			</div>
			<div class="uk-first-column"></div>
		</div>
	</div>
	<hr>
	<?php foreach ($documenti as $documento) { ?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Data caricamento");?>:</span> <?php echo date("d/m/Y H:i", strtotime($documento["documenti"]["data_creazione"]));?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Documento");?>:</span> <?php echo dfield($documento, "titolo");?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Tipo");?>:</span> <?php echo dfield($documento, "estensione");?>
			</div>
			<div class="uk-first-column uk-text-left uk-text-right@m">
				<a target="_blank" class="td_edit" title="<?php echo gtext("Scarica",false);?>" class="" href="<?php echo $this->baseUrl."/contenuti/documento/".$documento["documenti"]["id_doc"];?>">
					<span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/download.svg");?></span>
				</a>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtext("Non hai alcun documento riservato");?></p>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
