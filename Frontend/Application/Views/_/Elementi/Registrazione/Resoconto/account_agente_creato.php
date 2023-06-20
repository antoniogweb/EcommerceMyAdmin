<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("L'account è stato creato correttamente. Le è stata inviata una mail con le credenziali d'accesso che ha scelto");?>.</p>

<p>
<?php echo gtext("La sua richiesta di diventare un agente per il nostro ecommerce verrà valutata quanto prima.");?><br />
<?php echo gtext("Nel caso la sua richiesta venga approvata, le saranno assegnati uno o più codici coupon da usare nel nostro negozio e che potrà condividere con i suoi clienti.");?><br />
</p>

<p class="uk-margin-large"><a class="uk-button uk-button-default" href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Procedi nell' area riservata");?><span uk-icon="arrow-right"></span></a></p>
