<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

function set_valori_select(id_car)
{
	
	$(".hidden_caratt").val(id_car);
	
	$.ajax({
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

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1>Gestione <?php echo $tabella;?>: <?php echo $titoloPagina; ?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- show the top menù -->
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>

			<?php include($this->viewPath("steps"));?>
			
			<div class="box">
				<div class="box-header with-border main">
					<div class="notice_box">
						<?php echo $notice;?>
					</div>

					<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->controller."/caratteristiche/$id_page".$this->viewStatus;?>' method='POST'>
					
						<?php echo Html_Form::select("id_car","",$listaCaratteristiche,"lista_caratt form_select form-control",null,"yes");?>
						<div class="form-group">
							<label class="sr-only" for="titolo">Aggiungi caratteristica</label>
							<?php echo Html_Form::input("titolo","","form-control","titolo",'placeholder="Aggiungi se non definita"');?>
							
						</div>
						<?php echo Html_Form::select("id_cv","",$listaCarattVal,"lista_caratt_valori form_select form-control",null,"yes");?>
						<input class="hidden_caratt" type="hidden" name="id_car" value="" />
						<input class="submit_file btn btn-primary" type="submit" name="insertAction" value="Aggiungi">
						
					</form>
					<br />
					<!-- show the table -->
					<div class='recordsBox'>
						<?php if ($numeroCaratteristicheVal > 0) { ?>
						<?php echo $main;?>
						<?php } else {  ?>
						<span class="empty_list">Non è stata associata alcuna caratteristica</span>
						<?php } ?>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
