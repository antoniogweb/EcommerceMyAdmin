<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtextPlain("Liste nascita / regalo") => "",
);

$titoloPagina = gtextPlain("Liste nascita / regalo");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($liste) > 0) { ?>

<div class="uk-visible@m">
	<div class="uk-text-small uk-text-meta uk-grid-collapse uk-child-width-expand@s uk-text-center@s <?php if (!User::$isMobile) { ?>uk-flex-middle<?php } ?> uk-grid" uk-grid="">
		<div class="uk-first-column uk-text-left">
			<?php echo gtextPlain("Nome");?>
		</div>
		<div>
			<?php echo gtextPlain("Quantità");?>
		</div>
		<div><?php echo gtextPlain("Visualizzazioni");?></div>
		<div><?php echo gtextPlain("Creazione");?></div>
		<div><?php echo gtextPlain("Scadenza");?></div>
		<div><?php echo gtextPlain("Tipo");?></div>
		<div class="uk-width-1-5@m">
			<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-5 uk-child-width-1-3@m uk-child-width-expand@s uk-grid" uk-grid="">
				<div class="uk-text-center@s"><?php echo gtextPlain("Link");?></div>
				<div class="uk-text-center@s"><?php echo gtextPlain("Gestisci");?></div>
				<div class="uk-text-center@s"><?php echo gtextPlain("Disattiva");?></div>
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
		<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Nome");?>:</span> <?php echo $lista["liste_regalo"]["titolo"];?>
	</div>
	<div>
		<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Quantità");?>:</span> <?php echo ListeregaloModel::numeroProdotti($lista["liste_regalo"]["id_lista_regalo"]);?>
	</div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Visualizzazioni");?>:</span></div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Creazione");?>:</span> <?php echo smartDate($lista["liste_regalo"]["data_creazione"]);?></div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Scadenza");?>:</span>
		<?php if ($listaScaduta) { ?><span class="uk-text-danger"><?php } else { ?><span><?php } ?> 
		<?php echo smartDate($lista["liste_regalo"]["data_scadenza"]);?>
		</span>
	</div>
	<div><span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Tipo");?>:</span> <?php echo gtextPlain($lista["liste_regalo_tipi"]["titolo"]);?></div>
	<div class="uk-width-1-5@m">
		<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-5 uk-child-width-1-3@m uk-child-width-expand@s uk-grid" uk-grid="">
			<div class="uk-text-center@s">
				<a class=" uk-padding-small" title="<?php echo gtextAttr("Link",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/listeregalo/gestisci/".$lista["liste_regalo"]["id_lista_regalo"];?>#link-lista"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/link.svg");?></span></a>
			</div>
			<div class="uk-margin-remove-top uk-text-center@s">
				<a class="uk-padding-small" title="<?php echo gtextAttr("Modifica",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/listeregalo/gestisci/".$lista["liste_regalo"]["id_lista_regalo"];?>"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span></a>
			</div>
			<div class="uk-margin-remove-top uk-text-center@s">
				<?php if ($lista["liste_regalo"]["attivo"] == "Y") { ?>
				<a class="uk-text-danger uk-padding-small uk-text-bold td_edit" title="<?php echo gtextAttr("Disattiva la lista",false);?>" href="<?php echo $this->baseUrl."/liste-regalo/?valore=N&id_lista=".$lista["liste_regalo"]["id_lista_regalo"];?><?php echo $csrf_token_query_string;?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/close.svg");?></span></a>
				<?php } else { ?>
				<a class=" uk-padding-small uk-text-bold td_edit" title="<?php echo gtextAttr("Attiva la lista",false);?>" href="<?php echo $this->baseUrl."/liste-regalo/?valore=Y&id_lista=".$lista["liste_regalo"]["id_lista_regalo"];?><?php echo $csrf_token_query_string;?>"><span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/ban.svg");?></span></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<hr>
<?php } ?>

<?php } else { ?>
<p><?php echo gtextPlain("Non hai ancora creato alcuna lista nascita / regalo.");?></p>
<?php } ?>

<div class="uk-margin">
	<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/listeregalo/modifica/0";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/plus.svg");?></span></span> <?php echo gtextPlain("Crea lista nascita / regalo");?></a>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
