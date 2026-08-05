<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action === "classisconto") { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/classisconto/$id".$this->viewStatus;?>' method='POST'>
	
		<?php echo Html_Form::select("id_classe","",$listaClassi,"form-control",null,"yes");?>
		
		<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
		
	</form>
<?php } ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_c=<?php echo $id;?>">Aggiungi fascia</a></p>

<?php } ?>

<?php if ($this->action === "caratteristiche") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/caratteristiche/main";?>?partial=Y&nobuttons=N&id_c=<?php echo $id;?>"><i class="fa fa-plus-square-o"></i> <?php echo gtext("Aggiungi filtro");?></a></p>

<?php } ?>

<?php if ($this->action === "nazioni") { ?>

<div class="callout callout-info">
	<?php echo gtext("In questa scheda è possibile specificare le nazioni in cui i prodotti di questa categoria possono essere spediti.") ?><br />
	<b><?php echo gtext("Se non è inclusa alcuna nazione significa che i prodotti di questa categoria sono spedibili in tutte le nazioni attive.") ?></b>
</div>

<p><a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/nazioni/main?id_c=$id&partial=Y&cl_on_sv=Y&nobuttons=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi nazione");?></a></p>

<?php } ?>