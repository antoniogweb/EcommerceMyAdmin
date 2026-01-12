<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (v("attiva_modifica_massiva_codici") && isset($_GET["id_page"]) && PagesModel::isAttiva($_GET["id_page"])) { ?>
<div class="panel panel-info">
	<div class="panel-heading">
		<?php echo gtext("Modifica di gruppo");?>
	</div>
	<div class="panel-body">
		<form class="form-inline form-modifica-massiva">
			<?php echo Html_Form::input("codice","","form-control",null,"placeholder='".gtext("Codice")."'");?>
			<?php echo Html_Form::input("price","","form-control",null,"placeholder='".gtext("Prezzo")."'");?>
			<?php if (v("gestisci_sconti_combinazioni_separatamente")) { ?>
			<?php echo Html_Form::input("price_scontato","","form-control",null,"placeholder='".gtext("Prezzo scontato")."'");?>
			<?php } ?>
			<?php echo Html_Form::input("peso","","form-control",null,"placeholder='".gtext("Peso")."'");?>
			<?php echo Html_Form::input("giacenza","","form-control",null,"placeholder='".gtext("Giacenza")."'");?>
			<button class="btn btn-success btn-imposta-gruppo"><?php echo gtext("Imposta");?></button>
		</form>
	</div>
</div>

<script>
$(document).ready(function(){

	$("body").on("click", ".btn-imposta-gruppo", function(e){
		
		e.preventDefault();
		
		$(".form-modifica-massiva input").each(function(){
			var valore = $(this).val();
			var name = $(this).attr("name");
			
			if ($.trim(valore) != "" && $(".table-scaffolding").find("[name='"+name+"']").length > 0)
			{
				$(".table-scaffolding").find("[name='"+name+"']").val(valore);
			}
		});
		
	});
	
});
</script>
<?php } ?>

<?php if (v("mantieni_listini_esteri_sincronizzati_se_non_modificati") && $this->viewArgs["id_page"] != "tutti" && $this->viewArgs["listino"] != "tutti" && CombinazionilistiniModel::listinoPermesso($this->viewArgs["listino"]) && ProdottiModel::listinoModificato((int)$this->viewArgs["id_page"], $this->viewArgs["listino"])) { ?>
<div class="callout callout-warning">
	<?php echo gtext("Il listino")." <b>(".findTitoloDaCodice($this->viewArgs["listino"]).")</b> ".gtext("non è sincronizzato con quello di default")." <b>(".findTitoloDaCodice(v("nazione_default")).")</b>.";?>
	<a style="text-decoration:none;" class="badge badge-default pull-right" href="<?php echo $this->baseUrl."/combinazioni/main".$this->viewStatus."&sincronizza";?>"><?php echo gtext("Sincronizza il listino");?></a>
</div>
<?php } ?>

<?php echo $filtri;?>
