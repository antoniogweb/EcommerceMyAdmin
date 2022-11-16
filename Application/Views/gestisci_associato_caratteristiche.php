<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<a class="btn btn-success iframe help_aggiungi_caratteristiche <?php if (!v("nuova_modalita_caratteristiche") || $aggiuntaLibera) { ?>pull-right<?php } ?>" href="<?php echo $this->baseUrl."/caratteristichevalori/main?id_page=$id_page&partial=Y&cl_on_sv=Y&id_tipo_car=".$this->viewArgs["id_tipo_car"];?>"><i class="fa fa-pencil"></i> <?php echo gtext("Gestisci caratteristiche");?></a>

<?php if (v("nuova_modalita_caratteristiche")) { ?>
<!-- 	<p><a class="btn btn-primary iframe pull-right" href="<?php echo $this->baseUrl."/caratteristiche/main?partial=Y"?>"><i class="fa fa-edit"></i> Gestione caratteristiche</a> -->
	
	<div>
		<?php if ($aggiuntaLibera) { ?>
		<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/aggiungicaratteristica/$id_page".$this->viewStatus;?>' method='POST'>
			<?php echo Html_Form::input("titolo_car","","form-control auto","titolo_car",'data-provide="typeahead" autocomplete="off" placeholder="Caratteristica" source="caratteristiche/elenco/'.$sezione.'"');?>
			<?php echo Html_Form::input("titolo_carval","","form-control auto","titolo_carval",'data-provide="typeahead" autocomplete="off" placeholder="Valore" source="caratteristichevalori/elenco/'.$sezione.'"');?>
			<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
		</form>
		<?php } ?>
	</div>
	<br />
	
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
