<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<?php if (!isset($pageTitle)) { ?>
	<h1><?php echo gtext("Gestione");?> <?php echo gtext($tabella);?>: <?php if (strcmp($type,"update") === 0) { echo $titoloPagina; } else { echo "inserimento nuovo elemento";}?></h1>
	<?php } else { ?>
	<h1><?php echo $pageTitle;?></h1>
	<?php } ?>
</section>



