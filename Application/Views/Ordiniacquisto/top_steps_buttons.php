<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (OrdiniacquistoModel::haRigheDaRicevere($ordine["id_ordine_acquisto"])) { ?>
<a class="m-l pull-right btn btn-xs btn-info btn-rounded make_spinner" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/generaricezione/".$ordine["id_ordine_acquisto"];?>"><i class="fa fa-truck"></i> <?php echo gtextPlain("Genera ricezione");?></a>
<?php } ?>

<?php if (count(OrdiniacquistoModel::getSelectContatti($ordine["id_ordine_acquisto"])) > 0) { ?>
<a class="m-l pull-right btn btn-xs btn-warning btn-rounded iframe" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/inviapdfselezionecontatto/".$ordine["id_ordine_acquisto"]."?partial=Y&nobuttons=Y";?>"><i class="fa fa-envelope"></i> <?php echo gtextPlain("Invia PDF");?></a>
<?php } ?>

<a target="_blank" class="pull-right btn btn-xs btn-success btn-rounded" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/stampapdf/".$ordine["id_ordine_acquisto"]."?csrf=".User::$csrfToken;;?>"><i class="fa fa-file-pdf-o"></i> <?php echo gtextPlain("Stampa PDF");?></a>
