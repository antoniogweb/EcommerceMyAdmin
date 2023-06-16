<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("I miei coupon") => $this->baseUrl."/promozioni/elenco/",
	$promozione["codice"]	=>	"",
);

$titoloPagina = gtext("Coupon")." ".$promozione["codice"];

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "promozioni";

include(tpf("/Elementi/Pagine/riservata_top.php"));

$promoAttiva = PromozioniModel::g()->isActiveCoupon($promozione["codice"],0,false);
?>
<div style="display:none;" id="id_promo"><?php echo $promozione["id_p"];?></div>

<div class="uk-width-1-1 uk-flex uk-flex-top uk-grid" uk-grid>
    <div class="uk-width-1-1 uk-width-2-3@m uk-text-small">
        <?php echo gtext("Codice coupon");?>: <span class="uk-label" style="text-transform:none !important;"><?php echo $promozione["codice"];?></span><br />
        <?php echo gtext("Descrizione coupon");?>: <b><?php echo $promozione["titolo"];?></b><br />
        <?php echo gtext("Sconto");?>: <b><?php echo setPriceReverse($promozione["sconto"]);?><?php if ($promozione["tipo_sconto"] == "ASSOLUTO") { ?>€<?php } else { ?>%<?php } ?></b> (<?php echo $promozione["tipo_sconto"];?>)<br />
        <?php echo gtext("Il coupon è");?>: <b><?php echo $promoAttiva ? "<span class='uk-text-success'>".gtext("Attivo")."</span>" : "<span class='uk-text-danger'>".gtext("Disattivo")."</span>";?></b><br />
        <?php echo gtext("Data scadenza");?>: <b><?php echo smartDate($promozione["al"]);?></b>
    </div>
    <div class="uk-width-1-1 uk-width-1-3@m">
		<ul class="uk-subnav uk-subnav-divider uk-flex-right@s">
			<li><a href="<?php echo $this->baseUrl."/promozioni/modifica/".$promozione["id_p"];?>" class="uk-button uk-button-link"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span> <?php echo gtext("Modifica descrizione");?></a></li>
		</ul>
    </div>
</div>

<div class="uk-margin-large-top">
	<ul class="uk-subnav uk-subnav-pill tab_lista">
		<li><a class="link_ordini" href="#ordini"><span class="uk-margin-small-right uk-visible@s uk-icon"><?php include tpf("Elementi/Icone/Svg/tag.svg");?></span> <?php echo gtext("Ordini legati al coupon");?></a></li>
		<?php if ($promoAttiva) { ?>
        <li><a class="link_invii" href="#invii-codice"><span class="uk-margin-small-right uk-visible@s uk-icon"><?php include tpf("Elementi/Icone/Svg/mail.svg");?></span> <?php echo gtext("Invia codice");?></a></li>
        <?php } ?>
	</ul>
	
	<div class="tab_lista_box">
		<div id="ordini" class="uk-hidden">
			<div class="ordini-box uk-margin-large-top">
				<?php include(tpf("/Promozioni/ordini.php")); ?>
			</div>
		</div>
		<div id="invii-codice" class="uk-hidden">
			<div class="uk-margin-large-top">
				<?php include(tpf("/Promozioni/invii.php")); ?>
			</div>
		</div>
	</div>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
