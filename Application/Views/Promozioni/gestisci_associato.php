<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "categorie") { ?>

<div class="callout callout-info"><?php echo gtext("Se non si seleziona alcuna categoria la promo sarà utilizzabile sui prodotti di tutte le categorie.");?></div>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/categorie/$id".$this->viewStatus;?>' method='POST'>

	<span select2>
		<?php echo Html_Form::select("id_c","",$listaCategorie,null,null,"yes","style='min-width:200px'");?>
	</span>
	
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	
</form>
<br />
<?php } ?>

<?php if ($this->action == "pagine") { ?>

<div class="callout callout-info"><?php echo gtext("Se non si seleziona alcuna prodotto la promo sarà utilizzabile su tutti i prodotti.");?></div>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/pagine/$id".$this->viewStatus;?>' method='POST'>
	
	<span select2>
		<?php echo Html_Form::select("id_page","",$listaProdotti,null,null,"yes","style='min-width:200px'");?>
	</span>
	
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	
</form>
<br />

<?php } ?>

<?php if ($this->action == "tipi") { ?>

<div class="callout callout-info"><?php echo gtext("Se non si seleziona alcun tipo cliente la promo sarà utilizzabile per tutti i tipi di cliente.");?></div>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/tipi/$id".$this->viewStatus;?>' method='POST'>
	
	<span select2>
		<?php echo Html_Form::select("id_tipo_cliente","",$listaTipi,null,null,"yes","style='min-width:200px'");?>
	</span>
	
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	
</form>
<br />

<?php } ?>
