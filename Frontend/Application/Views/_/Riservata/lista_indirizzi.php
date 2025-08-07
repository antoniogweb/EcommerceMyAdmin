<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Indirizzi di spedizione") => "",
);

$titoloPagina = gtext("Indirizzi di spedizione");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "indirizzi";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($indirizzi) > 0) { ?>
	<div class="uk-visible@m">
		<div class="uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Indirizzo");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Cap");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Nazione");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Città");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Provincia");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Telefono");?>
			</div>
			<div class="uk-first-column"></div>
		</div>
	</div>
	<hr>
	<?php foreach ($indirizzi as $indirizzo) { ?>
	<div>
		<div class="uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Indirizzo");?>:</span> <?php echo $indirizzo["spedizioni"]["indirizzo_spedizione"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Cap");?>:</span> <?php echo $indirizzo["spedizioni"]["cap_spedizione"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Nazione");?>:</span> <?php echo nomeNazione($indirizzo["spedizioni"]["nazione_spedizione"]);?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Città");?>:</span> <?php echo $indirizzo["spedizioni"]["citta_spedizione"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Provincia");?>:</span> <?php echo in_array($indirizzo["spedizioni"]["nazione_spedizione"], NazioniModel::nazioniConProvince()) ? ProvinceModel::sFindTitoloDaCodice($indirizzo["spedizioni"]["provincia_spedizione"]) : $indirizzo["spedizioni"]["dprovincia_spedizione"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Telefono");?>:</span> <?php echo $indirizzo["spedizioni"]["telefono_spedizione"];?>
			</div>
			<div class="uk-first-column uk-text-left uk-text-right@m">
				<a class="td_edit" title="<?php echo gtext("Modifica",false);?>" class="" href="<?php echo $this->baseUrl."/gestisci-spedizione/".$indirizzo["spedizioni"]["id_spedizione"];?>">
					<span class="uk-icon uk-text-meta"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span>
				</a>
				
				<?php if (v("permetti_modifica_account")) { ?>
				<a class="uk-margin-left uk-text-bold td_edit uk-text-danger" title="<?php echo gtext("Elimina",false);?>" href="<?php echo $this->baseUrl."/riservata/indirizzi?del=".$indirizzo["spedizioni"]["id_spedizione"];?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/trash.svg");?></span></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtext("Non hai alcun indirizzo configurato");?></p>
<?php } ?>

<?php if (v("permetti_modifica_account")) { ?>
<div class="uk-margin">
	<a class="<?php echo v("classe_pulsanti_submit");?>" href="<?php echo $this->baseUrl."/gestisci-spedizione/0";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/plus.svg");?></span> <?php echo gtext("Aggiungi indirizzo");?></a>
</div>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
