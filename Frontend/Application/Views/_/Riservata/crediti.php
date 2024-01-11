<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Gestione crediti") => "",
);

$titoloPagina = gtext("Gestione crediti");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "crediti";

include(tpf("/Elementi/Pagine/riservata_top.php"));
$numeroCreditiAttivi = CreditiModel::gNumeroEuroRimasti(User::$id, true);
$idCatCrediti = CreditiModel::gIdCategory();
?>
<div class="uk-width-1-1 uk-flex uk-flex-top uk-grid uk-margin-medium-bottom" uk-grid>
	<div class="uk-width-1-1 uk-width-1-2@m uk-text-small">
		<b><?php echo gtext("Crediti attivi");?>:</b> <span class="uk-label uk-label-success uk-text-bold" style="vertical-align:top;"><?php echo number_format(CreditiModel::gNumeroEuroRimasti(User::$id, true),2,",",".");?></span><br /><br />
		<?php if ($numeroCreditiAttivi > 0) { ?>
		<?php echo gtext("Data scadenza");?>: <b><?php echo date("d-m-Y",strtotime(CreditiModel::dataScadenzaCrediti(User::$id)));?></b>
		<div class="uk-small uk-text-italic uk-meta"><?php echo gtext(sprintf("Se acquisti nuovi crediti, la data di scadenza verrà posticipata di %s mesi.",v("mesi_durata_crediti")));?></div>
		<?php } ?>
	</div>
	<div class="uk-width-1-1 uk-width-1-2@m uk-text-right">
		<?php if ($idCatCrediti) { ?>
		<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($idCatCrediti);?>"><span uk-icon="icon: plus"></span> <?php echo gtext("Acquista crediti");?></a>
		<?php } ?>
	</div>
</div>

<?php if (count($storico) > 0) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-small uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Data / Ora");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Crediti");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Scadenza");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Ordine");?>
			</div>
		</div>
	</div>
	<hr>
	<?php foreach ($storico as $credito) {
		$label = $credito["crediti"]["moltiplicatore_credito"] > 0 ? "success" : "danger";
		$segno = $credito["crediti"]["moltiplicatore_credito"] > 0 ? "+" : "";
	?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Data / Ora");?>:</span> <?php echo date("d-m-Y H:i", strtotime($credito["crediti"]["data_creazione"]));?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Crediti");?>:</span> <span class="uk-text-bold uk-text-<?php echo $label;?>"><?php echo $segno.$credito["crediti"]["numero_crediti"] * $credito["crediti"]["moltiplicatore_credito"];?></span>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Scadenza");?>:</span> <span class="uk-text-italic"><?php echo $credito["crediti"]["moltiplicatore_credito"] > 0 ? date("d-m-Y", strtotime($credito["crediti"]["data_scadenza"])) : "";?></span>
				<?php if (strtotime($credito["crediti"]["data_scadenza"]) < strtotime(date("Y-m-d"))) { ?>
				<br /><span class="uk-label uk-label-danger"><?php echo gtext("scaduti");?></span>
				<?php } ?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Ordine");?>:</span> <span><a href="<?php echo $this->baseUrl."/resoconto-acquisto/".$credito["orders"]["id_o"]."/".$credito["orders"]["cart_uid"]."?n=y";?>"><?php echo "#".$credito["orders"]["id_o"];?></a></span>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtext("Non hai alcun documento nella tua biblioteca");?></p>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
