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

<form action="<?php echo $this->baseUrl.$action;?>" method="POST">
	<div class="">
		<div class="uk-grid-column-small uk-child-width-1-2@s" uk-grid>
			<div class="first_of_grid uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Titolo della lista");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("titolo",$values['titolo'],"uk-input class_titolo",null);?>
				</div>
			</div>
			
			<div class="first_of_grid uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Nome del bimbo/a");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("nome_bambino",$values['nome_bambino'],"uk-input class_nome_bambino",null);?>
				</div>
			</div>
			
			<div class="first_of_grid uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Nome della mamma");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("genitore_1",$values['genitore_1'],"uk-input class_genitore_1",null);?>
				</div>
			</div>
			
			<div class="first_of_grid uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Nome del papà");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("genitore_2",$values['genitore_2'],"uk-input class_genitore_2",null);?>
				</div>
			</div>
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
