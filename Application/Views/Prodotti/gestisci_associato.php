<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "scaglioni" && v("scaglioni_in_prodotti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/scaglioni/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>"><?php echo gtext("Aggiungi scaglione");?></a></p>

<?php } ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>"><?php echo gtext("Aggiungi fascia");?></a></p>

<?php } ?>

<?php if ($this->action === "documenti" && v("documenti_in_prodotti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/documenti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi");?></a></p>

<?php } ?>

<?php if ($this->action === "testi") { ?>

<?php include(ROOT."/Application/Views/gestisci_associato_contenuti.php");?>

<?php } ?>

<?php if ($this->action === "personalizzazioni") { ?>

<form class="form-inline list_filter_form_top" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_pers","",$lista,"form-control help_tendina_personalizzazioni",null,"yes");?>
	
<!-- 	<input class="submit_file btn btn-primary btn-sm" type="submit" name="insertAction" value="Aggiungi"> -->
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	<input type="hidden" name="insertAction" value="Aggiungi" />
	
</form>

<?php } ?>

<?php if ($this->action === "categorie") { ?>

<form class="form-inline list_filter_form_top" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/".$this->action."/$id_page".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">

	<?php echo Html_Form::select("id_c","",$lista,"form-control help_tendina_categorie",null,"yes");?>
	
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="insertAction" value="Aggiungi"><i class="fa fa-save"></i> <?php echo gtext("Aggiungi");?></button>
	<input type="hidden" name="insertAction" value="Aggiungi" />
</form>

<?php } ?>

<?php if ($this->action === "correlati" || $this->action === "accessori") {
	include($this->viewPath("gestisci_correlati"));
} ?>

<?php include($this->viewPath("gestisci_associato_tag"));?>

<?php if ($this->action === "caratteristiche" && v("caratteristiche_in_prodotti")) { ?>

<?php include($this->viewPath("gestisci_associato_caratteristiche"));?>

<?php } ?>

<?php include($this->viewPath("gestisci_associato_pagine_correlate"));?>

<?php if ($this->action == "feedback" && v("abilita_feedback")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/feedback/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi feedback");?></a></p>

<?php } ?>

<?php if ($this->action == "regioni" && v("attiva_localizzazione_prodotto")) { ?>

<p>
	<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/regioni/main?id_page=$id_page&partial=Y&cl_on_sv=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi regione");?></a>
	
	<a class="btn btn-info iframe" href="<?php echo $this->baseUrl."/nazioni/main?id_page=$id_page&partial=Y&cl_on_sv=Y&nobuttons=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi nazione");?></a>
</p>

<?php } ?>

<?php include($this->viewPath("gestisci_associato_lingue"));?>
