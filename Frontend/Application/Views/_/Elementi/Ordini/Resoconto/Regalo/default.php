<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (trim($ordine["dedica"]) || trim($ordine["firma"])) { ?>
<h2 class="<?php echo v("classi_titoli_resoconto_ordine");?>"><?php echo gtext("Dedica e firma");?></h2>
<blockquote cite="#">
	<div class="uk-margin-small-bottom"><?php echo nl2br($ordine["dedica"]);?></div>
	<?php if (trim($ordine["firma"])) { ?>
	<footer><?php echo $ordine["firma"];?></footer>
	<?php } ?>
</blockquote>
<?php } ?> 
