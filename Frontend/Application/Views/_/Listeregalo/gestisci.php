<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Liste nascita / regalo") => $this->baseUrl."/liste-regalo/",
	$lista["titolo"]	=>	"",
);

$titoloPagina = gtext("Liste nascita / regalo");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<div style="display:none;" id="id_lista_regalo"><?php echo $lista["id_lista_regalo"];?></div>

<div class="uk-child-width-expand@s uk-flex uk-flex-middle uk-grid" uk-grid>
    <div>
        <?php echo gtext("Gestisci la tua lista");?>
    </div>
    <div class="uk-text-right">
		<a href="<?php echo $this->baseUrl."/listeregalo/modifica/".$lista["id_lista_regalo"];?>" class="uk-button uk-button-primary"><span uk-icon="icon: pencil"></span> <?php echo gtext("Modifica la lista");?></a>
    </div>
</div>

<div class="uk-margin-large-top">
    <ul uk-switcher="animation: uk-animation-fade" class="uk-child-width-expand" uk-tab>
        <li><a href="#prodotti-lista"><span class="uk-margin-small-right" uk-icon="tag"></span> <?php echo gtext("Prodotti");?></a></li>
        <li><a href="#"><span class="uk-margin-small-right" uk-icon="heart"></span> <?php echo gtext("Regali");?></a></li>
        <li><a href="#"><span class="uk-margin-small-right" uk-icon="link"></span> <?php echo gtext("Invia link");?></a></li>
    </ul>
    
    <ul class="uk-switcher uk-margin">
		<li id="prodotti-lista"></li>
		<li></li>
		<li></li>
	</ul>
</div>



<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
