<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($prodotti_correlati) > 0) { ?>
	<?php foreach ($prodotti_correlati as $corr) { ?>
	<li>
		<?php include(tpf("/Elementi/Categorie/prodotto.php")); ?>
	</li>
	<?php } ?>
<?php } ?> 
