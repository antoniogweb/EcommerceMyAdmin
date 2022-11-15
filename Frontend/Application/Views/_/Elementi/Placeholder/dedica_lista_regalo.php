<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (trim($record["dedica"])) { ?>
<b><?php echo gtext("Ecco la dedica che accompagna il regalo");?>:</b><br /><br />
<blockquote cite="#">
	<div class="uk-margin-small-bottom"><?php echo nl2br($record["dedica"]);?></div>
	<?php if (trim($record["firma"])) { ?>
	<footer><?php echo $record["firma"];?></footer>
	<?php } ?>
</blockquote>
<?php } ?>
