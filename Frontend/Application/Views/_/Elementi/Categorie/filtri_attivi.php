<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php foreach (CaratteristicheModel::$filtriUrl as $car => $carVs) { ?>
	<?php foreach ($carVs as $carV) { ?>
	<a class="uk-button uk-button-default uk-button-small" href="">
		<?php echo $carV;?>
		<span uk-icon="icon: close;ratio: 0.6"></span>
	</a>
	<?php } ?>
<?php } ?>
