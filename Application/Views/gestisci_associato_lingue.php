<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($this->action == "lingue") { ?>
	<div class="callout callout-info">
		<?php echo gtext("In questa scheda vengono definite le lingue in cui il prodotto/pagina è visibile.") ?>
		<?php echo gtext("È possibile includere o escludere lingue.") ?>
		<b><?php echo gtext("Se non è inclusa alcuna lingua significa che il prodotto/pagina è visibile in tutte le lingue.") ?></b>
	</div>
	<?php if (count($listaLingue) > 0) { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/lingue/$id_page".$this->viewStatus;?>' method='POST'>
	
		<?php echo Html_Form::select("id_lingua","",$listaLingue,"form-control",null,"yes");?>
		
		<input class="submit_file btn btn-success btn-sm" type="submit" name="includi" value="<?php echo gtext("Includi lingua")?>">
		<input class="submit_file btn btn-warning btn-sm" type="submit" name="escludi" value="<?php echo gtext("Escludi lingua")?>">
		
	</form>
	<br />
	<?php } ?>
<?php } ?>
