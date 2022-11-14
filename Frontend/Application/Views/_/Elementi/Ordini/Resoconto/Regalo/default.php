<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (trim($ordine["dedica"]) || trim($ordine["firma"])) { ?>
<h2 class="<?php echo v("classi_titoli_resoconto_ordine");?>"><?php echo gtext("Dedica e firma");?></h2>
<blockquote cite="#">
	<div class="uk-margin-small-bottom"><?php echo nl2br($ordine["dedica"]);?></div>
	<?php if (trim($ordine["firma"])) { ?>
	<footer><?php echo $ordine["firma"];?></footer>
	<?php } ?>
</blockquote>
	<?php if (v("attiva_liste_regalo")) { ?>
		<?php $dedica = OrdiniModel::g()->getElemendoDedica($ordine["id_o"]);?>
		<?php if ($dedica) { ?>
		<div class="uk-alert uk-alert-primary"><?php echo gtext("La mail con la dedica e la firma è stata inviata all'utente creatore della lista")." (<b>".$dedica["email"]."</b>) ".gtext("in data")." ".date("d/m/Y H:i", strtotime($dedica["data_creazione"]));?></div>
		<?php } ?>
	<?php } ?>
<?php } ?> 
