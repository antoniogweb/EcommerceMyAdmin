<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


<?php for ($i = 0; $i < count($records); $i++) { ?>

<div class="box_thumb" data-id="<?php echo $records[$i]['immagini']['id_immagine'];?>">

	<div class="box_thumb_up">
		<?php if (false) { ?>
			<?php if ($i !== 0) {?>
			<a class="a_moveup" href="<?php echo $this->baseUrl.'/immagini/moveup/'.$records[$i]['immagini']['id_immagine'];?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
			<?php } ?>
			
			<?php if ($i !== count($records)-1) {?>
			<a class="a_movedown" href="<?php echo $this->baseUrl.'/immagini/movedown/'.$records[$i]['immagini']['id_immagine'];?>"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
			<?php } ?>
		<?php } ?>
		
		<a title="elimina l'immagine" class="a_del pull-right" href="<?php echo $this->baseUrl.'/immagini/erase/'.$records[$i]['immagini']['id_immagine'];?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
		
		<a title="scarica l'immagine" class="a_download" target="_blank" href="<?php echo Domain::$name."/images/contents/".$records[$i]['immagini']["immagine"];?>"><i class="fa fa-download" aria-hidden="true"></i></a>
		
		<a title="ruota in senso orario" class="a_rotate_o" href="<?php echo $this->baseUrl.'/immagini/rotateo/'.$records[$i]['immagini']['id_immagine'];?>"><i class="fa fa-repeat" aria-hidden="true"></i></a>
		
	</div>
	
	<div class="box_thumb_down"><img src="<?php echo $this->baseUrl.'/thumb/contenuto/'.$records[$i]['immagini']['immagine']."/".rand(1,999999);?>"></div>
</div>

<?php } ?>
