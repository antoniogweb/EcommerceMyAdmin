<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<tr>
	<td><?php echo gtext("Stato ordine");?>:</td>
	<td><b><span class="label label-<?php echo labelStatoOrdine($ordine["stato"]);?>"><?php echo statoOrdine($ordine["stato"]);?></span></b></td>
</tr>
