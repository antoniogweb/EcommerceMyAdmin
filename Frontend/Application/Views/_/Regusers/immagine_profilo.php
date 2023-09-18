<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Modifica l'immagine del profilo")	=>	"",
);

$titoloPagina = gtext("Modifica l'immagine del profilo");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "immagine";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<div class="uk-container">
	
	
	<div class="newsroom_img_box">
	<?php if (strcmp($utenteProfilo["immagine"],"") !== 0) { ?>
		
		<div class="uk-margin">
			<img src='<?php echo $this->baseUrlSrc."/thumb/profilo/".$utenteProfilo["immagine"];?>'>
		</div>
		<div class="uk-margin">
			<a class='uk-button uk-button-danger' title='<?php echo gtext("Cancella immagine");?>' href='<?php echo $this->baseUrl."/immagine-profilo";?>?deleteFoto=y'><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/trash.svg");?></span> <?php echo gtext("Elimina immagine");?></a>
			
			<a class='uk-button uk-button-default' title='<?php echo gtext("Modifica immagine");?>' href='' uk-toggle="target: #form-modifica-immagine"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span> <?php echo gtext("Modifica immagine");?></a>
		</div>
	<?php } else { ?>
		<div class="uk-alert uk-alert-primary">
			<?php echo gtext("Non hai caricato alcuna immagine");?>
		</div>
	<?php } ?>
	</div>
	
	<?php echo $notice;?>
	
	<div id="form-modifica-immagine" class="uk-background-muted uk-padding" <?php if (strcmp($utenteProfilo["immagine"],"") !== 0 && !$notice) { ?>hidden<?php } ?>>
		<form  class="form_profilo" action="<?php echo $this->baseUrl."/immagine-profilo#form-modifica-immagine";?>" method="POST" enctype="multipart/form-data">
			<h3><?php echo gtext("Carica l'immagine");?></h3>
			
			<div class="uk-margin" uk-margin>
				<div uk-form-custom="target: true" class="class_immagine">
					<input type="file" name="immagine" aria-label="Custom controls">
					<input class="uk-input uk-form-width-medium" type="text" placeholder="<?php echo gtext("Seleziona il file");?>" aria-label="Custom controls" disabled>
				</div>
				
				<span class="uk-margin">
					<span class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m spinner uk-hidden" uk-spinner="ratio: .70"></span>
					<button class="<?php echo v("classe_pulsanti_submit");?> uk-width-1-1 uk-width-auto@m btn_submit_form" type="submit" name="updateAction"><?php echo gtext("Invia immagine");?></button>
				</span>
			</div>
		</form>
	</div>
</div>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
