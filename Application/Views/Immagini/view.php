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
		
		<a title="<?php echo gtext("elimina l'immagine");?>" class="a_del pull-right" href="<?php echo $this->baseUrl.'/immagini/erase/'.$records[$i]['immagini']['id_immagine'];?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
		
		<a title="<?php echo gtext("scarica l'immagine");?>" class="a_download" target="_blank" href="<?php echo Domain::$name."/images/contents/".$records[$i]['immagini']["immagine"];?>"><i class="fa fa-download" aria-hidden="true"></i></a>
		
		<a title="<?php echo gtext("edita i meta tag dell'immagine");?>" class="iframe" target="_blank" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/".$records[$i]['immagini']["id_immagine"];?>?partial=Y&nobuttons=Y"><i class="fa fa-pencil" aria-hidden="true"></i></a>
		
		<a title="<?php echo gtext("ruota in senso orario");?>" class="a_rotate_o" href="<?php echo $this->baseUrl.'/immagini/rotateo/'.$records[$i]['immagini']['id_immagine'];?>"><i class="fa fa-repeat" aria-hidden="true"></i></a>
		
	</div>
	
	<div class="box_thumb_down">
		<div class="row">
			<div class="col-md-3">
				<img src="<?php echo $this->baseUrl.'/thumb/contenuto/'.$records[$i]['immagini']['immagine']."/".rand(1,999999);?>">
			</div>
			<div class="col-md-9">
				<b><?php echo gtext("Nome file");?></b>: <?php echo $records[$i]['immagini']['immagine'] ? $records[$i]['immagini']['immagine'] : "--";?><br />
				<b><?php echo gtext("Alt tag");?></b>: <?php echo $records[$i]['immagini']['alt_tag'] ? $records[$i]['immagini']['alt_tag'] : "--";?>
				<?php if ($records[$i]['immagini']['id_immagine_tipologia']) { ?>
				<br /><b><?php echo gtext("Tipologia");?></b>: <?php echo ImmaginitipologieModel::sTitolo($records[$i]['immagini']['id_immagine_tipologia']);?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php } ?>
