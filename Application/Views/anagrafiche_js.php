<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

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

$(document).ready(function(){

	updateForm();
	
	$(".radio_cliente").change(function(){
		
		updateForm();
	
	});
	
	if ($('[name="id_user"]').length > 0 && $('[name="id_spedizione"]').length > 0)
	{
		$('[name="id_user"]').on('select2:select', function (e) {
			
			var idUser = $(this).val();
			
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
