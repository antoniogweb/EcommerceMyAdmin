<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "gruppi") { ?>
	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/regusers/gruppi/$id".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
	
		<?php echo Html_Form::select("id_group","",$listaGruppi,null,"combobox","yes");?>
		
		<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
		
	</form>
<?php } ?>

<?php if ($this->action == "spedizioni") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/spedizioni/form/insert";?>?partial=Y&nobuttons=Y&id_user=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi indirizzo di spedizione")?></a></p>

<?php } ?>

<?php if ($this->action == "promozioni") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/promozioni/form/insert";?>?partial=Y&nobuttons=Y&id_user=<?php echo $id;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi codice coupon")?></a></p>

<?php } ?>
