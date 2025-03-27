<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div><a class="<?php echo v("classe_pulsanti_submit");?>" href='<?php echo Url::getRoot()."redirect-to-gateway/".$this->ordine["id_o"]."/".$this->ordine["cart_uid"]."/".$this->ordine["admin_token"];?>'><span uk-icon="credit-card"></span> <?php echo gtext("Paga adesso");?></a></div> 
