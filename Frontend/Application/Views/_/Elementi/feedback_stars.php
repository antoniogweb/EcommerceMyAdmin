<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$stellePiene = floor($punteggio);
$stelleVuote = (5 - $stellePiene);
if (!isset($ratio))
	$ratio = 0.7;
?>
<?php for ($i=0; $i<$stellePiene; $i++) { ?>
<div class="uk-text-warning" uk-icon="icon: star; ratio: <?php echo $ratio;?>"></div>
<?php } ?>
<?php for ($i=0; $i<$stelleVuote; $i++) { ?>
<!-- <div class="uk-text-warning" uk-icon="icon: star; ratio: <?php echo $ratio;?>"></div> -->
<?php } ?>
