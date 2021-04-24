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

<form class="form-inline list_filter_form_top" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_pers","",$lista,"form-control",null,"yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>

<?php } ?>

<?php if ($this->action === "tag") { ?>

<form class="form-inline list_filter_form_top" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_tag","",$lista,"form-control",null,"yes");?>
	
	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi">
	
</form>

<?php } ?>

<?php if ($this->action === "caratteristiche" && v("caratteristiche_in_prodotti")) { ?>

<p><a class="btn btn-primary iframe pull-right" href="<?php echo $this->baseUrl."/caratteristiche/main?partial=Y"?>"><i class="fa fa-edit"></i> Gestione caratteristiche</a>

<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/caratteristichevalori/main?id_page=$id_page&partial=Y&cl_on_sv=Y&id_tipo_car=".$this->viewArgs["id_tipo_car"];?>"><i class="fa fa-plus"></i> Aggiungi</a></p>

<?php } ?>

<?php if ($this->action === "paginecorrelate") { ?>

<?php foreach ($tabSezioni as $section => $titleSection) {
		if ($this->viewArgs["pcorr_sec"] != $section)
			continue;
?>
	<p>
		<a class="btn btn-primary iframe pull-right" href="<?php echo $this->baseUrl."/$section/main?partial=Y"?>"><i class="fa fa-edit"></i> Gestione <?php echo $titleSection;?></a>

		<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/$section/main?id_pcorr=$id_page&partial=Y&cl_on_sv=Y&pcorr_sec=$section";?>"><i class="fa fa-plus"></i> Aggiungi</a>
	</p>
	<?php } ?>

<?php } ?>

<?php if ($this->action == "feedback" && v("abilita_feedback")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/feedback/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>">Aggiungi feedback</a></p>

<?php } ?>
