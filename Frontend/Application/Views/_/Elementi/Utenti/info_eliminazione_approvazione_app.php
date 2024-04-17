<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!empty($app)) { ?>
<h3 class="uk-card-title"><?php echo gtext("Tutti i dati raccolti tramite l'APP sono stati eliminati");?></h3>
<p><?php echo gtext("L'approvazione all'utilizzo dell'APP Instagram da parte del sito è stata correttamente eliminata, assieme a tutti i dati raccolti dall'account Instagram collegato all'APP.");?></p>
<p><?php echo gtext("Codice univoco dell'operazione")?>: <span class="uk-label uk-label-primary"><?php echo $app["confirmation_code"];?></span></p>
<?php } ?>
