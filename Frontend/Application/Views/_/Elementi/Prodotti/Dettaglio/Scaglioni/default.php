<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (count($scaglioni) > 0) {
if (!isset($widthScaglioni))
	$widthScaglioni = "uk-width-2-3@m";
?>
<div class="<?php echo $widthScaglioni;?> uk-margin-medium-bottom">
	<table class="uk-text-small uk-table uk-table-divider uk-table-striped uk-table-small">
		<thead>
			<tr>
				<th><?php echo gtext("Quantità");?></th>
				<th><?php echo gtext("Sconto");?></th>
			</tr>
		</thead>
		<?php foreach ($scaglioni as $q => $sconto) { ?>
		<tr>
			<td>da <?php echo $q;?> <?php echo gtext("unità");?></td>
			<td><?php echo $sconto." %";?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<?php } ?>
