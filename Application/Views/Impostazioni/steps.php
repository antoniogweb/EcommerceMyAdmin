<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($type !== "insert") { ?>

<ul class="nav_dettaglio nav nav-tabs">
	<li <?php echo $posizioni['main'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/form/update/$id".$this->viewStatus;?>"><?php echo gtext("Principali");?></a></li>
	<?php if (v("lista_variabili_gestibili")) { ?>
	<li <?php echo $posizioni['variabili'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/variabili/$id".$this->viewStatus;?>"><?php echo gtext("Informazioni generali");?></a></li>
	<?php } ?>
	<?php if (v("lista_variabili_funzionamento_ecommerce")) { ?>
	<li <?php echo $posizioni['ecommerce'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/ecommerce/$id".$this->viewStatus;?>"><?php echo gtext("Impostazioni pubblicazione");?></a></li>
	<?php } ?>
	<?php if (v("lista_variabili_opzioni_google")) { ?>
	<li <?php echo $posizioni['google'];?>><a href="<?php echo $this->baseUrl."/".$this->controller."/google/$id".$this->viewStatus;?>"><?php echo gtext("Opzioni Google / Facebook");?></a></li>
	<?php } ?>
</ul>

<?php } else { ?>

<?php } ?>

<div style="clear:left;"></div>
