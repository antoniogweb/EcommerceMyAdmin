<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!empty($cliente)) { ?>
<h3 class="uk-card-title"><?php echo gtext("Informazioni sullo stato del suo account");?></h3>
<p><?php echo gtext("Il suo account e tutti i suoi dati sono stati correttamente eliminati in data")?> <b><?php echo date("d/m/Y", $cliente["time_eliminazione"]);?></b>.</p>
<p><?php echo gtext("Codice univoco dell'operazione")?>: <span class="uk-label uk-label-primary"><?php echo $cliente["token_eliminazione"];?></span></p>
<p class="uk-text-warning"><?php echo gtext("Per motivi fiscali, nel caso abbia eseguito degli ordini nel nostro negozio online, i suoi dati rimarranno salvati come dati anagrafici all'interno degli ordini suddetti.");?> <?php echo gtext("Per maggiori informazioni in merito si prega di contattare il negozio.")?></p>
<?php } ?>
