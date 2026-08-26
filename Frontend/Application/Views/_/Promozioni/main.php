<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtextPlain("I miei coupon") => "",
);

$titoloPagina = gtextPlain("I miei coupon");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "promozioni";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($promozioni) > 0) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtextPlain("Titolo");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Codice");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Attivo dal - al");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Tipo sconto");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Valore");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Attivo");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtextPlain("Gestisci");?>
			</div>
		</div>
	</div>
	<hr>
	<?php foreach ($promozioni as $promozione) { ?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Titolo");?>:</span> <?php echo $promozione["promozioni"]["titolo"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Codice");?>:</span> <b><?php echo $promozione["promozioni"]["codice"];?></b>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Attivo dal - al");?>:</span> <?php echo smartDate($promozione["promozioni"]["dal"]);?> / <?php echo smartDate($promozione["promozioni"]["al"]);?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Tipo sconto");?>:</span> <?php echo $promozione["promozioni"]["tipo_sconto"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Valore");?>:</span> <?php echo setPriceReverse($promozione["promozioni"]["sconto"]);?><?php if ($promozione["promozioni"]["tipo_sconto"] == "ASSOLUTO") { ?>€<?php } else { ?>%<?php } ?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtextPlain("Attivo");?>:</span> <?php if ($promozione["promozioni"]["attivo"] == "Y") { ?><span class="uk-icon uk-text uk-text-success"><?php include tpf("Elementi/Icone/Svg/check.svg");?></span><?php } else { ?><span class="uk-icon uk-text uk-text-danger"><?php include tpf("Elementi/Icone/Svg/ban.svg");?></span><?php } ?>
			</div>
			<div class="uk-first-column">
				<a class="td_edit" title="<?php echo gtextAttr("Dettagli coupon",false);?>" class="" href="<?php echo $this->baseUrl."/promozioni/gestisci/".$promozione["promozioni"]["id_p"];?>">
					<span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span>
				</a>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtextPlain("Non hai alcun coupon assegnato a te.");?></p>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
