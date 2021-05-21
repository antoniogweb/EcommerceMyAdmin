<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if ($testo["immagine"]) { ?>
	<?php if ($urlLink) { ?>
	<a <?php echo $target;?> href="<?php echo $urlLink;?>">
	<?php } ?>
		<?php if ($testo["immagine_2x"]) { ?>
			<img srcset="
			<?php echo Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?> 1x, 
			<?php echo Domain::$publicUrl."/thumb/widget2x/".$testo["id_t"]."/".$testo["immagine_2x"];?> 2x" />
		<?php } else { ?>
			<img <?php echo htmlentitydecode($testo["attributi"]);?> src="<?php echo Domain::$publicUrl."/thumb/widget/".$testo["id_t"]."/".$testo["immagine"];?>" />
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
