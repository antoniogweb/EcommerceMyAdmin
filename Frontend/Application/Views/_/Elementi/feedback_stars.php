<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$stellePiene = floor($punteggio);
$stelleVuote = (5 - $stellePiene);
?>
<?php for ($i=0; $i<$stellePiene; $i++) { ?>
<div class="color-yellow" uk-icon="icon: filled-star; ratio: 0.7"></div>
<?php } ?>
<?php for ($i=0; $i<$stelleVuote; $i++) { ?>
<div class="color-yellow" uk-icon="icon: star; ratio: 0.7"></div>
<?php } ?>
