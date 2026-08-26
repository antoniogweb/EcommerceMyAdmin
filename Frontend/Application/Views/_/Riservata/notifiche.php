<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Area riservata")	=>	$this->baseUrl."/".Url::routeToUrl("area-riservata"),
	gtextPlain("Notifiche") => "",
);

$titoloPagina = gtextPlain("Notifiche");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "notifiche";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<form action="<?php echo $this->baseUrl."/user-notifications/";?>" class="uk-grid-small uk-margin-medium-bottom" uk-grid method="GET">
	<div class="uk-width-1-5@s">
		<?php echo Html_Form::input("dal",$this->viewArgs["dal"],"uk-input date_picker_generale",null,'placeholder="'.gtextAttr("Dal").'"');?>
	</div>
	<div class="uk-width-1-5@s">
		<?php echo Html_Form::input("al",$this->viewArgs["al"],"uk-input date_picker_generale",null,'placeholder="'.gtextAttr("Al").'"');?>
	</div>
	<div class="uk-width-1-5@s">
		<?php echo Html_Form::select("id_c",$this->viewArgs["id_c"],$categorieDaLeggere,"uk-input",null,'yes','placeholder="'.gtextAttr("Categoria").'"');?>
	</div>
	<div class="uk-width-1-5@s">
		<?php echo Html_Form::select("id_page",$this->viewArgs["id_page"],$pagineDaLeggere,"uk-input",null,'yes','placeholder="'.gtextAttr("Prodotto").'"');?>
	</div>
	<div class="uk-width-1-5@s">
		<button type="submit" class="uk-button uk-button-primary uk-width-1-1"><?php echo gtextPlain("Filtra");?></button>
	</div>
</form>

<?php if (count($notificheDaLeggere) > 0) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-small uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtextPlain("Data");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<?php echo gtextPlain("Categoria");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<?php echo gtextPlain("Prodotto");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<?php echo gtextPlain("Tipo documento");?>
			</div>
			<div class="uk-first-column uk-text-right">
				<?php echo gtextPlain("Documento");?>
			</div>
		</div>
	</div>
	<hr>
	<?php foreach ($notificheDaLeggere as $notifica) { ?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Data");?>:</span> <?php echo date("d/m/Y", strtotime($notifica["documenti"]["data_file_upload"]));?>
			</div>
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Categoria");?>:</span> <?php echo genericField($notifica, "title", "categories", "categorie_tradotte");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Prodotto");?>:</span> <?php echo genericField($notifica, "title", "pages", "pagine_tradotte");?>
			</div>
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Tipo documento");?>:</span> <?php echo $notifica["tipi_documento"]["titolo"];?>
			</div>
			<div class="uk-first-column uk-text-right">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Documento");?>:</span> <a target="_blank" href="<?php echo $this->baseUrl."/contenuti/documento/".$notifica["documenti"]["id_doc"];?>"><?php echo genericField($notifica, "titolo", "documenti", "documenti_tradotti");?></a>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtextPlain("Non hai alcuna notifica da leggere");?></p>
<?php } ?>

<?php 
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
