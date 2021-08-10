<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if ($this->action == "scaglioni" && v("scaglioni_in_prodotti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/scaglioni/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>">Aggiungi scaglione</a></p>

<?php } ?>

<?php if ($this->action === "contenuti") { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/contenuti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>">Aggiungi fascia</a></p>

<?php } ?>

<?php if ($this->action === "documenti" && v("documenti_in_prodotti")) { ?>

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/documenti/form/insert";?>?partial=Y&nobuttons=N&id_page=<?php echo $id_page;?>"><i class="fa fa-plus-circle"></i> <?php echo gtext("Aggiungi")?></a></p>

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

<?php if (v("nuova_modalita_caratteristiche")) { ?>
	<p><a class="btn btn-primary iframe pull-right" href="<?php echo $this->baseUrl."/caratteristiche/main?partial=Y"?>"><i class="fa fa-edit"></i> Gestione caratteristiche</a>

	<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/caratteristichevalori/main?id_page=$id_page&partial=Y&cl_on_sv=Y&id_tipo_car=".$this->viewArgs["id_tipo_car"];?>"><i class="fa fa-plus"></i> Aggiungi</a></p>
<?php } else { ?>
	
	<script type="text/javascript">

	function set_valori_select(id_car)
	{
		$(".hidden_caratt").val(id_car);
		
		$.ajaxQueue({
			url: "<?php echo $this->baseUrl."/caratteristiche/lista/";?>" + id_car,
			async: false,
			cache:false,
			dataType: "xml",
			success: function(content){
				
				var temp_tip = $(".lista_caratt_valori").find("option:selected").attr("value");
				
				$(".lista_caratt_valori").empty();
				$(content).find("lista").find("option").each(function(){
					if (temp_tip == $(this).attr("value"))
					{
						$(".lista_caratt_valori").append("<option value='"+$(this).attr("value")+"' selected='"+$(this).attr("value")+"'>"+$(this).text()+"</option>");
					}
					else
					{
						$(".lista_caratt_valori").append("<option value='"+$(this).attr("value")+"'>"+$(this).text()+"</option>");
					}
				});
				
			}
		});
	}

	$(document).ready(function(){

		$(".lista_caratt option[value=<?php echo $lastCar;?>]").attr('selected','selected');
		
		set_valori_select($(".lista_caratt").find("option:selected").attr("value"));
		
		$(".lista_caratt").change(function(){
		
			var id_car = encodeURIComponent($(this).val());
			
			set_valori_select(id_car);
		
		});
		
	});

	</script>

	<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/caratteristiche/$id_page".$this->viewStatus;?>' method='POST'>
	
		<?php echo Html_Form::select("id_car","",$listaCaratteristiche,"lista_caratt form_select form-control",null,"yes");?>
		<div class="form-group">
			<label class="sr-only" for="titolo">Aggiungi caratteristica</label>
<!-- 			<?php echo Html_Form::input("titolo","","form-control","titolo",'placeholder="Aggiungi se non definita"');?> -->
		</div>
		<?php echo Html_Form::select("id_cv","",$listaCarattVal,"lista_caratt_valori form_select form-control",null,"yes");?>
		<input class="hidden_caratt" type="hidden" name="id_car" value="" />
		<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
		
	</form><br />
<?php } ?>

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

<p><a class="iframe btn btn-success" href="<?php echo $this->baseUrl."/feedback/form/insert";?>?partial=Y&nobuttons=Y&id_page=<?php echo $id_page;?>"><i class="fa fa-plus"></i> Aggiungi feedback</a></p>

<?php } ?>

<?php if ($this->action == "regioni" && v("attiva_localizzazione_prodotto")) { ?>

<p>
	<a class="btn btn-success iframe" href="<?php echo $this->baseUrl."/regioni/main?id_page=$id_page&partial=Y&cl_on_sv=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi regione");?></a>
	
	<a class="btn btn-info iframe" href="<?php echo $this->baseUrl."/nazioni/main?id_page=$id_page&partial=Y&cl_on_sv=Y&nobuttons=Y";?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi nazione");?></a>
</p>

<?php } ?>

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
