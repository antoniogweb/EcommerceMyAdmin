<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Liste nascita / regalo") => "",
);

$titoloPagina = gtext("Liste nascita / regalo");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($liste) > 0) { ?>

<div class="uk-visible@m">
	<div class="uk-text-small uk-text-meta uk-grid-collapse uk-child-width-expand@s uk-text-center@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="">
		<div class="uk-first-column uk-text-left">
			<?php echo gtext("Nome");?>
		</div>
		<div>
			<?php echo gtext("Quantità");?>
		</div>
		<div><?php echo gtext("Visualizzazioni");?></div>
		<div><?php echo gtext("Creazione");?></div>
		<div><?php echo gtext("Scadenza");?></div>
		<div><?php echo gtext("Tipo");?></div>
		<div class="uk-width-1-5@m">
			<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-5 uk-child-width-1-3@m uk-child-width-expand@s uk-grid" uk-grid="">
				<div class="uk-text-center@s"><?php echo gtext("Link");?></div>
				<div class="uk-text-center@s"><?php echo gtext("Gestisci");?></div>
				<div class="uk-text-center@s"><?php echo gtext("Disattiva");?></div>
			</div>
		</div>
	</div>
</div>
<hr>
<?php foreach ($liste as $lista) {
	$listaScaduta = ListeregaloModel::scaduta($lista["liste_regalo"]["id_lista_regalo"]);
?>
<div class="uk-text-small uk-grid-collapse uk-child-width-expand@s uk-text-center@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="">
	<div class="uk-first-column uk-text-left">
		<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Nome");?>:</span> <?php echo $lista["liste_regalo"]["titolo"];?>
	</div>
	<div>
		<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Quantità");?>:</span> <?php echo ListeregaloModel::numeroProdotti($lista["liste_regalo"]["id_lista_regalo"]);?>
	</div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtext("Visualizzazioni");?>:</span></div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtext("Creazione");?>:</span> <?php echo smartDate($lista["liste_regalo"]["data_creazione"]);?></div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtext("Scadenza");?>:</span>
		<?php if ($listaScaduta) { ?><span class="uk-text-danger"><?php } else { ?><span><?php } ?> 
		<?php echo smartDate($lista["liste_regalo"]["data_scadenza"]);?>
		</span>
	</div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtext("Tipo");?>:</span> <?php echo gtext($lista["liste_regalo_tipi"]["titolo"]);?></div>
	<div class="uk-width-1-5@m">
		<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-5 uk-child-width-1-3@m uk-child-width-expand@s uk-grid" uk-grid="">
			<div class="uk-text-center@s">
				<a class=" uk-padding-small" title="<?php echo gtext("Link",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/listeregalo/gestisci/".$lista["liste_regalo"]["id_lista_regalo"];?>#link-lista" uk-icon="icon: link"></a>
			</div>
			<div class="uk-margin-remove-top uk-text-center@s">
				<a class="uk-padding-small" title="<?php echo gtext("Modifica",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/listeregalo/gestisci/".$lista["liste_regalo"]["id_lista_regalo"];?>" uk-icon="icon: pencil"></a>
			</div>
			<div class="uk-margin-remove-top uk-text-center@s">
				<?php if ($lista["liste_regalo"]["attivo"] == "Y") { ?>
				<a class="uk-text-danger uk-padding-small uk-text-bold td_edit" title="<?php echo gtext("Disattiva la lista",false);?>" href="<?php echo $this->baseUrl."/liste-regalo/?valore=N&id_lista=".$lista["liste_regalo"]["id_lista_regalo"];?>" uk-icon="icon: close"></a>
				<?php } else { ?>
				<a class=" uk-padding-small uk-text-bold td_edit" title="<?php echo gtext("Attiva la lista",false);?>" href="<?php echo $this->baseUrl."/liste-regalo/?valore=Y&id_lista=".$lista["liste_regalo"]["id_lista_regalo"];?>" uk-icon="icon: ban"></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<hr>
<?php } ?>

<?php } else { ?>
<p><?php echo gtext("Non hai ancora creato alcuna lista nascita / regalo.");?></p>
<?php } ?>

<div class="uk-margin">
	<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/listeregalo/modifica/0";?>"><span uk-icon="plus"></span> <?php echo gtext("Crea lista nascita / regalo");?></a>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
