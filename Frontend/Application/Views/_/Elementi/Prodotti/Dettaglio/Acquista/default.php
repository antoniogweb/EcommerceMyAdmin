<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="info-product">
	<h1><?php echo field($p, "title");?></h1>
	
	<?php if (v("abilita_feedback")) { ?>
	<?php include(tpf(ElementitemaModel::p("FEEDBACK_MEDIO_PRODOTTO","", array(
		"titolo"	=>	"Valutazione media prodotto",
		"percorso"	=>	"Elementi/Prodotti/Dettaglio/FeedbackMedio",
	)))); ?>
	<?php } ?>
	
	
	<?php include(tpf(ElementitemaModel::p("PREZZO_DETTAGLIO")));?>
	
	<div class="uk-margin">
		<?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
	</div>
	
	<div class="uk-margin">
		<ul class="uk-list uk-text-small uk-margin-remove">
			<?php if ($p["pages"]["codice"]) { ?>
			<li><span class="uk-text-muted"><?php echo gtext("Codice");?>: </span><span class="codice_value"><?php echo $p["pages"]["codice"];?></span></li>
			<?php } ?>
			<li><span class="uk-text-muted"><?php echo gtext("Peso");?>: </span><span class="peso_value"><?php echo setPriceReverse($p["pages"]["peso"]);?></span> kg</li>
			<?php if (isset($marchioCorrente) && count($marchioCorrente) > 0) { ?>
			<li><span class="uk-text-muted"><?php echo gtext("Marchio");?>: </span><a href='<?php echo $this->baseUrl."/".getMarchioUrlAlias($marchioCorrente["marchi"]["id_marchio"]);?>'><?php echo mfield($marchioCorrente,"titolo");?></a></li>
			<?php } ?>
			<?php include(tpf(ElementitemaModel::p("GIACENZA")));?>
		</ul>
	</div>
	
	<?php include(tpf(ElementitemaModel::p("SCAGLIONI")));?>

	<?php include(tpf("/Elementi/Pagine/accessori.php"));?>
	
	<?php if (acquistabile($p["pages"]["id_page"])) { ?>
		<?php if (!User::$isMobile) { ?>
		<div class="uk-width-1-1 uk-width-2-3@m">
			<?php include(tpf(ElementitemaModel::p("QUANTITA")));?>
		</div>
		<?php } else { ?>
			<input name="quantita" class="uk-input quantita_input" type="hidden" value="<?php echo getQtaDaCarrello();?>" min="1" style="font-size: 14px;">
		<?php } ?>
		
		<?php if ($haVarianti || $haPersonalizzazioni) { ?>
		<div class="box_accessorio accessorio_principale">
			<?php include(tpf(ElementitemaModel::p("VARIANTI")));?>
			
			<?php include(tpf(ElementitemaModel::p("PERSONALIZZAZIONI")));?>
			
			<div class="uk-margin-small uk-text-small uk-text-danger errore_combinazione"></div>
		</div>
		<?php } ?>
		
		<div class="uk-text-small uk-text-danger errore_giacenza"></div>
		
		<?php include(tpf(ElementitemaModel::p("AGGIUNGI_AL_CARRELLO_DETTAGLIO")));?>
		
		<?php include(tpf(ElementitemaModel::p("PULSANTE_ACQUISTA_DETTAGLIO")));?>
		
		<?php include(tpf(ElementitemaModel::p("WISHLIST_DETTAGLIO")));?>
	<?php } ?>
	
	<hr class="uk-margin-medium-top">
	
	<?php include(tpf(ElementitemaModel::p("CONDIVIDI_DETTAGLIO")));?>
</div>

