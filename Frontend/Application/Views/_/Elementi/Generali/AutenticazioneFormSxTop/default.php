<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<h3><?php echo gtext("Accedi");?></h3>
<div class="uk-text-meta"><?php echo gtext("Inserisci E-mail e Password per continuare come utente loggato.");?><?php if (!User::$isPhone) { ?><br /><br /><?php } ?></div> 
