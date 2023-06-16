<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("I miei coupon") => $this->baseUrl."/liste-regalo/",
);

$breadcrumb[gtext("Modifica la descrizione")] = "";

$titoloPagina = gtext("Modifica la descrizione");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-text-center">
	<?php echo $notice; ?>
</div>
<form class="form_lista_regalo" action="<?php echo $this->baseUrl.$action;?>" method="POST">
	<div class="">
		<div class="uk-grid uk-grid-column-small uk-child-width-1-2@s" uk-grid>
			<div class="uk-margin uk-margin-remove-bottom">
				<label class="uk-form-label"><?php echo gtext("Descrizione del coupon");?> *</label>
				<div class="uk-form-controls">
					<?php echo Html_Form::input("titolo",$values['titolo'],"uk-input class_titolo",null);?>
				</div>
			</div>
		</div>
	</div>

	<div class="uk-margin">
		<div class="<?php echo v("classe_pulsanti_submit");?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
		<?php if ($id === 0) { ?>
		<input class="<?php echo v("classe_pulsanti_submit");?> btn_submit_form" type="submit" name="insertAction" value="<?php echo gtext("Salva", false);?>" />
		<?php } else { ?>
		<input class="<?php echo v("classe_pulsanti_submit");?> btn_submit_form" type="submit" name="updateAction" value="<?php echo gtext("Salva", false);?>" />
		<?php } ?>
	</div>
	
</form>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
