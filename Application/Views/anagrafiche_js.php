<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div id="modale_cliente" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<!--<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo gtext("Anagrafica cliente esistente")?></h4>
			</div>-->
			<div class="modal-body">
				<h3 class="titolo_avviso_cliente_presente"><?php echo gtext("Attenzione, l'indirizzo email [INDIRIZZO_EMAIL] che hai inserito nella testata dell'ordine è già presente nel database dei clienti.");?></h3>
				<?php echo gtext("Questo significa che il cliente è già presente nel database.");?><br /><br />
				<?php echo gtext("Premi il pulsante");?> <b><?php echo gtext("Importa dati cliente da anagrafica");?></b> <?php echo gtext("se vuoi che i dati del clienti vengano caricati dall'anagrafica cliente ed inseriti nell'ordine corrente.")?><br /><br />
				<?php echo gtext("Premi il pulsante");?> <b><?php echo gtext("Continua e sovrascrivi dati cliente");?></b> <?php echo gtext("se vuoi continuare inserendo i dati del cliente. Nel momento in cui salverai l'ordine, l'anagrafica cliente verrà sovrascritta con i dati che hai inserito nella testata dell'ordine.")?><br />
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-info importa_dati_anagrafica" data-dismiss="modal"><i class="fa fa-upload"></i> <?php echo gtext("Importa dati cliente da anagrafica");?></button>
				<button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-save"></i> <?php echo gtext("Continua e sovrascrivi dati cliente");?></button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

var idUtenteTrovato = 0;
var emailUtenteTrovato = "";

function updateForm()
{
	var tipo_cliente = $(".radio_cliente option:selected").attr("value");
	
	if (tipo_cliente == "privato")
	{
		$(".ragione_sociale").css("display","none");
		$(".p_iva").css("display","none");
		$(".nome").css("display","block");
		$(".cognome").css("display","block");
	}
	else if (tipo_cliente == "libero_professionista")
	{
		$(".ragione_sociale").css("display","none");
		$(".p_iva").css("display","block");
		$(".nome").css("display","block");
		$(".cognome").css("display","block");
	}
	else
	{
		$(".ragione_sociale").css("display","block");
		$(".p_iva").css("display","block");
		<?php if (!v("nome_cognome_anche_azienda")) { ?>
		$(".nome").css("display","none");
		$(".cognome").css("display","none");
		<?php } ?>
	}
}

function sistemaTendinaProvincia(val)
{
	if (val == "IT")
	{
		$(".box_dprovincia").css("display","none");
		$(".box_provincia").css("display","block");
	}
	else
	{
		$(".box_dprovincia").css("display","block");
		$(".box_provincia").css("display","none");
	}
}

function controllaAnagraficaEsistente()
{
	if ($('[name="id_user"]').length > 0 && $('[name="id_spedizione"]').length > 0)
	{
		var idUser = $('[name="id_user"]').val();
		var email = $('[name="email"]').val();
		
		if (idUser == 0)
		{
			$.ajaxQueue({
				url: baseUrl + "/regusers/emailesistente?email=" + email,
				cache:false,
				async: true,
				dataType: "json",
				success: function(content){
					if (content.id > 0 && content.email != "")
					{
						idUtenteTrovato = content.id;
						emailUtenteTrovato = content.email;
						
						var titolo = $(".titolo_avviso_cliente_presente").text().replace("[INDIRIZZO_EMAIL]", emailUtenteTrovato);
						
						$(".titolo_avviso_cliente_presente").text(titolo);
						
						$('#modale_cliente').modal({show:true});
					}
				}
			});
		}
	}
}

function caricaTestataESpedizioniUtente(idUser)
{
	$.ajaxQueue({
		url: baseUrl + "/regusers/form/update/" + idUser + "?esporta_json",
		cache:false,
		async: true,
		dataType: "json",
		success: function(content){
			
			for (var key in content)
			{
				if ($('[name="'+key+'"]').length > 0)
					$('[name="'+key+'"]').val(content[key]);
			}
			
			$.ajaxQueue({
				url: baseUrl + "/regusers/spedizioni/" + idUser + "?esporta_json&formato_json=select2",
				cache:false,
				async: true,
				dataType: "json",
				success: function(content){
					
					var selectSpedizione = $('[name="id_spedizione"]');
					
					selectSpedizione.find('option').remove();
					selectSpedizione.append("<option value='0'>-- nessun indirizzo selezionato --</option>");
					
					var res = content.results;
					
					for (var i =0; i < res.length; i++)
					{
						selectSpedizione.append("<option value='" + res[i].id + "'>" + res[i].text + "</option>");
					}
					
					selectSpedizione.select2("destroy");
					selectSpedizione.select2();
				}
			});
		}
	});
}

$(document).ready(function(){

	updateForm();
	
	$("body").on("click", ".importa_dati_anagrafica", function(e){
		
		if (idUtenteTrovato != 0 && emailUtenteTrovato != "")
		{
			$('[name="id_user"]').append("<option value='"+idUtenteTrovato+"'>"+emailUtenteTrovato+"</option>");
			$('[name="id_user"]').val(idUtenteTrovato);
			$('[name="id_user"]').trigger('change');
			caricaTestataESpedizioniUtente(idUtenteTrovato);
		}
		
	});
	
	
	$(".radio_cliente").change(function(){
		
		updateForm();
	
	});
	
	$("body").on("keyup", "[name='email']", debounce(function(e){
		
		controllaAnagraficaEsistente();
		
	},1000));
	
	if ($('[name="id_user"]').length > 0 && $('[name="id_spedizione"]').length > 0)
	{
		$('[name="id_user"]').on('select2:select', function (e) {
			
			var idUser = $(this).val();
			
			caricaTestataESpedizioniUtente(idUser);
			
		});
		
		$('[name="id_spedizione"]').on('select2:select', function (e) {
			
			var idSpedizione = $(this).val();
			
			$.ajaxQueue({
				url: baseUrl + "/spedizioni/form/update/" + idSpedizione + "?esporta_json",
				cache:false,
				async: true,
				dataType: "json",
				success: function(content){
					
					for (var key in content)
					{
						if ($('[name="'+key+'"]').length > 0)
							$('[name="'+key+'"]').val(content[key]);
					}
					
				}
			});
			
		});
	}
	
	if ($("[name='nazione']").length > 0)
		sistemaTendinaProvincia($("[name='nazione']").val());
	
	$("body").on("change", "[name='nazione']", function(e){
		
		sistemaTendinaProvincia($(this).val());
		
	});
});

</script>
