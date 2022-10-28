<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($mobileCallbackClass))
	$mobileCallbackClass = "cart_item_row_mobile";

if (!isset($increaseCallbackClass))
	$increaseCallbackClass = "cart_item_quantity_increase";

if (!isset($decreaseCallbackClass))
	$decreaseCallbackClass = "cart_item_quantity_decrease";

if (!isset($qtaMin))
	$qtaMin = 0;

if (!isset($qtaMax))
	$qtaMax = 9999;

if (!isset($qtaMaxSelect))
	$qtaMaxSelect = 30;

if (!isset($iconUp))
	$iconUp = "chevron-up";

if (!isset($iconDown))
	$iconDown = "chevron-down";
?>
<?php if (User::$isMobile) { ?>
	<div class="select_box cart_select_box" back-color="<?php echo $backColor;?>"><?php echo Html_Form::select("quantity", $quantitaRigaCarrello, array_combine(range(1,$qtaMaxSelect),range(1,$qtaMaxSelect)),"uk-select item_quantity $mobileCallbackClass", null, "yes", "rel='".$idRigaCarrello."' style='background-color:$backColor; !important'");?></div>
<?php } else { ?>
	<?php if (!v("mostra_piu_meno_modifica_quantita")) { ?>
	<input rel="<?php echo $idRigaCarrello;?>" class="uk-input item_quantity" name="quantity" type="number" value="<?php echo $quantitaRigaCarrello;?>" min="1" 	style="background-color:<?php echo $backColor;?> !important" />
	<?php } else { ?>
	<div class="uk-flex uk-flex-middle uk-flex-center box_quantity uk-border-rounded">
		<input rel="<?php echo $idRigaCarrello;?>" disabled class="uk-padding-remove uk-form-width-xsmall uk-input item_quantity" name="quantity" type="text" value="<?php echo $quantitaRigaCarrello;?>" min="1" style="max-width:25px;border:none;background-color:<?php echo $backColor;?> !important" />
		<div class="uk-text-center">
			<a lvalue="<?php echo $qtaMax;?>" style="display:block" class="<?php if ((int)$qtaMax <= 1) { ?>uk-text-meta<?php } ?> <?php echo $increaseCallbackClass;?>" href="#"><span uk-icon="icon: <?php echo $iconUp;?>;ratio: 1"></span></a>
			<a lvalue="<?php echo $qtaMin;?>" style="display:block" class="<?php if ((int)$quantitaRigaCarrello <= 1) { ?>uk-text-meta<?php } ?> <?php echo $decreaseCallbackClass;?>" href="#"><span uk-icon="icon: <?php echo $iconDown;?>;ratio: 1"></span></a>
		</div>
	</div>
	<?php } ?>
<?php } ?>
