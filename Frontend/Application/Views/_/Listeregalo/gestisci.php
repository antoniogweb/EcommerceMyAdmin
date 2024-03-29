<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Liste nascita / regalo") => $this->baseUrl."/liste-regalo/",
	$lista["titolo"]	=>	"",
);

$titoloPagina = gtext("Lista")." ".$lista["titolo"];

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<div style="display:none;" id="id_lista_regalo"><?php echo $lista["id_lista_regalo"];?></div>

<div class="uk-width-1-1 uk-flex uk-flex-top uk-grid" uk-grid>
    <div class="uk-width-1-1 uk-width-1-3@m uk-text-small">
        <?php echo gtext("Codice della lista");?>: <span class="uk-label uk-text-lowercase"><?php echo $lista["codice"];?></span><br />
        <?php echo gtext("La lista è");?>: <b><?php echo $lista["attivo"] == "Y" ? "<span class='uk-text-success'>".gtext("Attiva")."</span>" : "<span class='uk-text-danger'>".gtext("Disattiva")."</span>";?></b><br />
        <?php echo gtext("Data scadenza");?>: <b><?php echo smartDate($lista["data_scadenza"]);?></b> <?php if (ListeregaloModel::scaduta($lista["id_lista_regalo"])) { ?>(<span class="uk-text-danger"><?php echo gtext("scaduta");?></span>)<?php } ?>
    </div>
    <div class="uk-width-1-1 uk-width-2-3@m">
		<ul class="uk-subnav uk-subnav-divider uk-flex-right@s">
			<li><a href="<?php echo $this->baseUrl."/listeregalo/modifica/".$lista["id_lista_regalo"];?>" class="uk-button uk-button-link"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span> <?php echo gtext("Modifica dati");?></a></li>
			
			<?php if (ListeregaloModel::attiva($lista["id_lista_regalo"])) { ?>
			<li><a target="_blank" href="<?php echo $this->baseUrl."/".ListeregaloModel::getUrlAlias($lista["id_lista_regalo"]);?>" share-text="<?php echo gtext("Lista regalo");?>" share-title="<?php echo $lista["titolo"];?>" class="share-link uk-button uk-button-link"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/social.svg");?></span> <?php echo gtext("Condividi");?></a></li>
			
			<li><a target="_blank" href="<?php echo $this->baseUrl."/".ListeregaloModel::getUrlAlias($lista["id_lista_regalo"]);?>" class="uk-button uk-button-link"><?php echo gtext("Vai alla lista");?> <span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/arrow-right.svg");?></span></a></li>
			<?php } ?>
		</ul>
    </div>
</div>

<div class="uk-margin-large-top">
	<ul class="uk-subnav uk-subnav-pill tab_lista">
		<li><a class="link_prodotti" href="#prodotti-lista"><span class="uk-margin-small-right uk-visible@s uk-icon"><?php include tpf("Elementi/Icone/Svg/tag.svg");?></span> <?php echo gtext("Prodotti");?></a></li>
        <li><a href="#regali-lista"><span class="uk-margin-small-right uk-visible@s uk-icon"><?php include tpf("Elementi/Icone/Svg/cuore.svg");?></span> <?php echo gtext("Regali");?></a></li>
        <li><a class="link_lista" href="#link-lista"><span class="uk-margin-small-right uk-visible@s uk-icon"><?php include tpf("Elementi/Icone/Svg/link.svg");?></span> <?php echo gtext("Invia link");?></a></li>
	</ul>
	
	<div class="tab_lista_box">
		<div id="prodotti-lista" class="uk-hidden">
			<div class="prodotti-lista-box uk-margin-large-top">
				<?php
				$regalati = false;
				include(tpf("/Listeregalo/prodotti.php"));?>
			</div>
		</div>
		<div id="regali-lista" class="uk-hidden">
			<div class="prodotti-lista-regali uk-margin-large-top">
				<?php
				$regalati = true;
				include(tpf("/Listeregalo/prodotti.php"));?>
			</div>
		</div>
		<div id="link-lista" class="uk-hidden">
			<div class="uk-margin-large-top">
				<?php include(tpf("/Listeregalo/link.php"));?>
			</div>
		</div>
	</div>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
