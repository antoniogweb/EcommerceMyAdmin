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

<div class="uk-width-1-1 uk-flex uk-flex-middle uk-grid uk-grid-collapse" uk-grid>
    <div class="uk-width-1-2">
        <?php echo gtext("Codice della lista");?>: <span class="uk-label uk-text-lowercase"><?php echo $lista["codice"];?></span>
    </div>
    <div class="uk-width-1-2 uk-text-right">
		<ul class="uk-subnav uk-subnav-divider uk-flex-right">
			<li><a href="<?php echo $this->baseUrl."/listeregalo/modifica/".$lista["id_lista_regalo"];?>" class="uk-button uk-button-link"><span uk-icon="icon: pencil"></span> <?php echo gtext("Modifica dati");?></a></li>
		
			<li><a target="_blank" href="<?php echo $this->baseUrl."/lista-regalo/".$lista["codice"]."/".$lista["alias"].".html";?>" class="uk-button uk-button-link"><?php echo gtext("Vai alla lista");?> <span uk-icon="icon: arrow-right"></span></a></li>
		</ul>
    </div>
</div>

<div class="uk-margin-large-top">
	<ul class="uk-subnav uk-subnav-pill" uk-switcher="active:0">
		<li><a href="#prodotti-lista"><span class="uk-margin-small-right uk-visible@s" uk-icon="tag"></span> <?php echo gtext("Prodotti");?></a></li>
        <li><a href="#"><span class="uk-margin-small-right uk-visible@s" uk-icon="heart"></span> <?php echo gtext("Regali");?></a></li>
        <li><a href="#"><span class="uk-margin-small-right uk-visible@s" uk-icon="link"></span> <?php echo gtext("Invia link");?></a></li>
	</ul>
	
    <ul class="uk-switcher uk-margin">
		<li id="prodotti-lista">
			<div class="prodotti-lista-box uk-margin-large-top">
				<?php include(tpf("/Listeregalo/prodotti.php"));?>
			</div>
		</li>
		<li></li>
		<li></li>
	</ul>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
