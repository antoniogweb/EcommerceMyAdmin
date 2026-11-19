<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($testo["immagine"]) {
	$attributiImmagine = htmlentitydecode($testo["attributi"]);
	$altDefinitoNegliAttributi = preg_match('/(?:^|\s)alt\s*=/i', $attributiImmagine);
	$altImmagine = empty($testo["alt"]) && $altDefinitoNegliAttributi
		? ''
		: ($testo["alt"] ? ' alt="'.htmlentitydecode($testo["alt"]).'" ' : ($urlLink && empty($testo["testo_link"]) ? ' alt="'.gtextAttr("Apri immagine").'" ' : ' alt="" '));
?>
	<?php if ($urlLink) { ?>
	<a <?php echo $target;?> href="<?php echo $urlLink;?>">
	<?php } ?>
		<?php $ext = Files_Upload::sFileExtension($testo["immagine"]);?>
		<?php if ($ext != "svg") { ?>
			<?php if ($testo["immagine_2x"]) { ?>
				<img <?php echo $altImmagine;?> <?php echo $attributiImmagine;?> uk-img data-srcset="<?php echo Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?> 1x, <?php echo Domain::$publicUrl."/thumb/widget2x/".$testo["id_t"]."/".$testo["immagine_2x"];?> 2x" srcset="<?php echo Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?> 1x, <?php echo Domain::$publicUrl."/thumb/widget2x/".$testo["id_t"]."/".$testo["immagine_2x"];?> 2x"/>
			<?php } else { ?>
				<img <?php echo $altImmagine;?> <?php echo $attributiImmagine;?> src="<?php echo Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?>" />
			<?php } ?>
		<?php } else { ?>
			 <img <?php echo $altImmagine;?> <?php echo $attributiImmagine;?> src="<?php echo Domain::$publicUrl."/images/widgets/".$testo["immagine"];?>" />
		<?php } ?>
			<?php if ($testo["testo_link"]) { ?>
			<span><?php echo $testo["testo_link"];?></span>
			<?php } ?>
	<?php if ($urlLink) { ?>
	</a>
	<?php } ?>
<?php } else { ?>
	<?php echo $testo["valore"]; ?>
<?php } ?>
