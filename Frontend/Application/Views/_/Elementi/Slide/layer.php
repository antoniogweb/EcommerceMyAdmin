<?php if (!defined('EG')) die('Direct access not allowed!');

$url = ContenutiModel::getUrlContenuto($layer);

$posizione = getAllineamentoLayer($layer);

// print_r($layer);

if ($layer["contenuti"]["tipo_layer"] == "TESTO") { ?>
<div id="<?php echo $layer["contenuti"]["id_cont"];?>" class="<?php echo $posizione; ?> eg-position-large uk-position-small">
	<h1 <?php echo getAnimazioneLayer($layer, 100);?>><?php echo htmlentitydecode(contfield($layer, "titolo"));?></h1>
	<p <?php echo getAnimazioneLayer($layer, 200);?>><?php echo htmlentitydecode(contfield($layer, "descrizione"));?></p>
	<?php if ($url) { ?>
	<a <?php echo getAnimazioneLayer($layer, 300);?> class="uk-button uk-button-default" href="<?php echo $url;?>"><?php echo gtext("Scopri");?></a>
	<?php } ?>
</div>
<?php } else if ($layer["contenuti"]["tipo_layer"] == "IMMAGINE") { ?>
<div id="<?php echo $layer["contenuti"]["id_cont"];?>" class="uk-width-1-2 uk-width-2-3@m <?php echo $posizione; ?> eg-position-small uk-position-small">
	<img style="max-width:90%;" <?php echo getAnimazioneLayer($layer, 200);?> src="<?php echo $this->baseUrlSrc."/thumb/slidelayer/".$layer["contenuti"]["immagine_1"]?>" />
</div>
<?php } ?>
