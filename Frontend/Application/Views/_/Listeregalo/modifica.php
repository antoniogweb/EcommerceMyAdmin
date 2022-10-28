<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Liste nascita / regalo") => $this->baseUrl."/liste-regalo/",
);

if ($id === 0)
{
	$breadcrumb[gtext("Crea la tua lista")] = "";
	
	$titoloPagina = gtext("Crea la tua lista");
}
else
{
	$breadcrumb[gtext("Modifica la tua lista")] = "";
	
	$titoloPagina = gtext("Modifica la tua lista");
}

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-text-center">
	<?php echo $notice; ?>
</div>
<form class="form_lista_regalo" action="<?php echo $this->baseUrl.$action;?>" method="POST">
	<div class="">
		<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
			<?php $attributiIdTipo = ($id === 0) ? "" : "disabled";?>
			
			<div class="first_of_grid uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Tipo lista");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::select("id_lista_tipo",$values['id_lista_tipo'],$selectTipi,"uk-select class_id_lista_tipo",null,"yes",$attributiIdTipo);?>
				</div>
			</div>
			
			<div class="uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Nome della lista");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("titolo",$values['titolo'],"uk-input class_titolo",null);?>
				</div>
			</div>
			
			<?php if (array_key_exists("nome_bambino",$values)) { ?>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom campo_lista <?php echo implode(" ",ListeregalotipiModel::campoPresenteInTipi("nome_bambino"));?>">
				<label class="uk-form-label"><?php echo gtext("Nome del bimbo/a");?> <?php if (ListeregalotipiModel::obbligatorio($idTipoLista, "nome_bambino")) {?>*<?php } ?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("nome_bambino",$values['nome_bambino'],"uk-input class_nome_bambino",null);?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (array_key_exists("genitore_1",$values)) { ?>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom campo_lista <?php echo implode(" ",ListeregalotipiModel::campoPresenteInTipi("genitore_1"));?>">
				<label class="uk-form-label"><?php echo gtext("Nome genitore 1");?> <?php if (ListeregalotipiModel::obbligatorio($idTipoLista, "genitore_1")) {?>*<?php } ?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("genitore_1",$values['genitore_1'],"uk-input class_genitore_1",null);?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (array_key_exists("genitore_2",$values)) { ?>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom campo_lista <?php echo implode(" ",ListeregalotipiModel::campoPresenteInTipi("genitore_2"));?>">
				<label class="uk-form-label"><?php echo gtext("Nome genitore 2");?> <?php if (ListeregalotipiModel::obbligatorio($idTipoLista, "genitore_2")) {?>*<?php } ?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("genitore_2",$values['genitore_2'],"uk-input class_genitore_2",null);?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (array_key_exists("sesso",$values)) { ?>
			<div class="uk-margin uk-margin-remove-bottom campo_lista <?php echo implode(" ",ListeregalotipiModel::campoPresenteInTipi("sesso"));?>">
				<label class="uk-form-label"><?php echo gtext("Sesso");?> <?php if (ListeregalotipiModel::obbligatorio($idTipoLista, "sesso")) {?>*<?php } ?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::select("sesso",$values['sesso'],array(
						"M"	=>	gtext("Maschio"),
						"F"	=>	gtext("Femmina"),
					),"uk-select class_sesso",null,"yes");?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (array_key_exists("data_nascita",$values)) { ?>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom campo_lista <?php echo implode(" ",ListeregalotipiModel::campoPresenteInTipi("data_nascita"));?>">
				<label class="uk-form-label"><?php echo gtext("Data prevista nascita");?> <?php if (ListeregalotipiModel::obbligatorio($idTipoLista, "data_nascita")) {?>*<?php } ?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("data_nascita",$values['data_nascita'] == "00-00-0000" ? "" : $values['data_nascita'],"uk-input class_data_nascita datepicker",null,"autocomplete='new-password'");?>
				</div>
			</div>
			<?php } ?>
			
			<?php if (array_key_exists("data_battesimo",$values)) { ?>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom campo_lista <?php echo implode(" ",ListeregalotipiModel::campoPresenteInTipi("data_battesimo"));?>">
				<label class="uk-form-label"><?php echo gtext("Data battesimo");?> <?php if (ListeregalotipiModel::obbligatorio($idTipoLista, "data_battesimo")) {?>*<?php } ?></label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("data_battesimo",$values['data_battesimo'] == "00-00-0000" ? "" : $values['data_battesimo'],"uk-input class_data_battesimo datepicker",null,"autocomplete='new-password'");?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>

	<div class="uk-margin">
		<div class="uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<?php if ($id === 0) { ?>
		<input class="uk-button uk-button-secondary btn_submit_form" type="submit" name="insertAction" value="<?php echo gtext("Salva", false);?>" />
		<?php } else { ?>
		<input class="uk-button uk-button-secondary btn_submit_form" type="submit" name="updateAction" value="<?php echo gtext("Salva", false);?>" />
		<?php } ?>
	</div>
	
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
