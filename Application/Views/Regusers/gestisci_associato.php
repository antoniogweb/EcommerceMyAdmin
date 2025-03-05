<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "gruppi") { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/regusers/gruppi/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
	
		<div select2="" style="display:inline-block;">
			<?php echo Html_Form::select("id_group","",$listaGruppi,null,null,"yes","style='width:200px;'");?>
		</div>
<!-- 		<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi"> -->
		
		<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
		<input type="hidden" name="insertAction" value="Aggiungi" />
		
	</form>
<?php } ?>

<?php if ($this->action == "spedizioni") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/spedizioni/form/insert";?>?partial=Y&nobuttons=Y&id_user=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi indirizzo di spedizione")?></a></p>

<?php } ?>

<?php if ($this->action == "promozioni") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/promozioni/form/insert";?>?partial=Y&nobuttons=Y&id_user=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi codice coupon")?></a></p>

<?php } ?>

<?php if ($this->action === "documenti" && v("documenti_in_clienti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/documenti/form/insert";?>?partial=Y&nobuttons=N&id_page=0&id_user=<?php echo $id;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></p>

<?php } ?>