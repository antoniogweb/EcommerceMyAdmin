<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "scaglioni" && v("scaglioni_in_prodotti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/scaglioni/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>">Aggiungi scaglione</a></p>

<?php } ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>">Aggiungi fascia</a></p>

<?php } ?>

<?php if ($this->action === "documenti" && v("documenti_in_prodotti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/documenti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>">Aggiungi</a></p>

<?php } ?>

<?php if ($this->action === "testi") { ?>

<?php include(ROOT."/Application/Views/gestisci_associato_contenuti.php");?>

<?php } ?>

<?php if ($this->action === "personalizzazioni") { ?>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_pers","",$lista,"form-control",null,"yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>

<?php } ?>

<?php if ($this->action === "tag") { ?>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_tag","",$lista,"form-control",null,"yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>

<?php } ?>
