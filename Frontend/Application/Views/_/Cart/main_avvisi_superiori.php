<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $numeroGiftCardInCarrello = CartModel::numeroGifCartInCarrello(); ?>
<?php
include(tpf(ElementitemaModel::p("AVVISO_LISTA_SELEZIONATA","", array(
	"titolo"	=>	"Avviso quando hai una lista selezionata",
	"percorso"	=>	"Elementi/ListaRegalo/AvvisoCarrelloCheckout",
))));
?>
<?php if (!checkQtaCartFull()) { ?>
<div class="<?php echo v("alert_error_class");?>"><?php echo gtext("Attenzione, alcune righe nel tuo carrello hanno una quantità maggiore di quella presente a magazzino.")?></div>
<?php } ?>
<?php if ($numeroGiftCardInCarrello > v("numero_massimo_gift_card")) { ?>
<div class="<?php echo v("alert_error_class");?>"><?php echo str_replace("[N]",v("numero_massimo_gift_card"),gtext("Attenzione, non è possibile inserire nel carrello più di [N] gift card"));?></div>
<?php } ?>
<?php if (isset($_GET["evidenzia"]) && CartelementiModel::haErrori()) { ?>
<div class="<?php echo v("alert_error_class");?>"><?php echo gtext("Attenzione, controllare i campi evidenziati relativi alle Gift Card.");?></div>
<?php } ?>
<?php if (!hasActiveCoupon() && PromozioniModel::$erroreCouponUtente) { ?>
<div class="<?php echo v("alert_error_class");?>"><?php echo gtext(PromozioniModel::$erroreCouponUtente);?></div>
<?php } ?>