<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php if (VariabiliModel::verificaCondizioni($voce["condizioni"])) { ?>
	<?php if (count($voce["figli"]) > 0) { ?>
	<li class="<?php echo MenuadminModel::classeCurrent($voce["controller"]);?> treeview <?php echo $voce["classe"];?>">
		<a href="#">
			<i class="fa <?php echo $voce["icona"];?>"></i>
			<span><?php echo $voce["titolo"];?></span>
		</a>
		<ul class="treeview-menu">
			<?php foreach ($voce["figli"] as $vf) {
				$controllers = !empty($vf["controller"]) ? $vf["controller"] : $voce["controller"];
			?>
				<?php if ($vf["tipo"] == "LABEL") { ?>
				<li class="dropdown-header"><?php echo $vf["titolo"];?></li>
				<?php } else { ?>
				<li class="<?php echo MenuadminModel::classeCurrent($controllers, $vf["action"]);?>"><a href="<?php echo Url::getRoot().ltrim($vf["url"],"/");?>"><i class="fa <?php echo $vf["icona"];?>"></i> <?php echo $vf["titolo"];?></a></li>
				<?php } ?>
			<?php } ?>
		</ul>
	</li>
	<?php } else { ?>
	<li class="<?php echo MenuadminModel::classeCurrent($voce["controller"]);?> <?php echo $voce["classe"];?>">
		<a href="<?php echo Url::getRoot().ltrim($voce["url"],"/");?>">
			<i class="fa <?php echo $voce["icona"];?>"></i> <span><?php echo $voce["titolo"];?></span>
		</a>
	</li>
	<?php } ?>
<?php } ?>
