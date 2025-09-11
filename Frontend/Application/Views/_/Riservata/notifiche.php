<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Gestione notifiche") => "",
);

$titoloPagina = gtext("Gestione notifiche");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "notifiche";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<?php if (count($notificheDaLeggere) > 0) { ?>
	<form class="uk-grid-small uk-margin-medium-bottom" uk-grid>
		<div class="uk-width-1-5@s">
			<input class="uk-input date_picker_generale" type="text" aria-label="<?php echo gtext("Da");?>" placeholder="<?php echo gtext("Da");?>">
		</div>
		<div class="uk-width-1-5@s">
			<input class="uk-input date_picker_generale" type="text" aria-label="<?php echo gtext("A");?>" placeholder="<?php echo gtext("A");?>">
		</div>
		<div class="uk-width-1-5@s">
			<input class="uk-input" type="text" placeholder="<?php echo gtext("Categoria");?>" aria-label="25">
		</div>
		<div class="uk-width-1-5@s">
			<input class="uk-input" type="text" placeholder="<?php echo gtext("Prodotto");?>" aria-label="25">
		</div>
	</form>
	
	<div class="uk-visible@m">
		<div class="uk-text-small uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Data");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Categoria");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Prodotto");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Tipo documento");?>
			</div>
			<div class="uk-first-column uk-text-right">
				<?php echo gtext("Documento");?>
			</div>
		</div>
	</div>
	<hr>
	<?php foreach ($notificheDaLeggere as $notifica) { ?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Data");?>:</span> <?php echo date("d/m/Y", strtotime($notifica["documenti"]["data_documento"]));?>
			</div>
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Categoria");?>:</span> <?php echo genericField($notifica, "title", "categories", "categorie_tradotte");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Prodotto");?>:</span> <?php echo genericField($notifica, "title", "pages", "pagine_tradotte");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Tipo documento");?>:</span> <?php echo $notifica["tipi_documento"]["titolo"];?>
			</div>
			<div class="uk-first-column uk-text-right">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Documento");?>:</span> <a target="_blank" href="<?php echo $this->baseUrl."/contenuti/documento/".$notifica["documenti"]["id_doc"];?>"><?php echo genericField($notifica, "titolo", "documenti", "documenti_tradotti");?></a>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtext("Non hai alcuna notifica da leggere");?></p>
<?php } ?>

<?php 
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
