<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<a href="<?php echo Domain::$publicUrl.$linguaUrl."contenuti/recuperacarrello/".v("token_recupera_carrello")."?cart_uid=".sanitizeAll($record["cart_uid"]);?>"><?php echo gtext("Concludi ordine");?></a>
