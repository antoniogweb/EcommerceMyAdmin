<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


<?php for ($i = 0; $i < count($records); $i++) {?>

<div class="box_thumb" data-id="<?php echo $records[$i]['immagini_archivi']['id_immagine_archivio'];?>">

	<div class="box_thumb_up">
		<a title="<?php echo gtext("elimina l'immagine");?>" class="a_del pull-right" href="<?php echo $this->baseUrl.'/immaginiarchivi/erase/'.$records[$i]['immagini_archivi']['id_immagine_archivio'];?>?csrf=<?php echo User::$csrfToken;?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
		
		<a title="<?php echo gtext("scarica l'immagine");?>" class="a_download" target="_blank" href="<?php echo Domain::$name."/".Parametri::$cartellaImmaginiArchivi."/".$records[$i]['immagini_archivi']["immagine"];?>"><i class="fa fa-download" aria-hidden="true"></i></a>
		
		<a title="<?php echo gtext("edita i meta tag dell'immagine");?>" class="iframe" target="_blank" href="<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/form/update/".$records[$i]['immagini_archivi']["id_immagine_archivio"];?>?partial=Y&nobuttons=Y&contesto=<?php echo $contesto;?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
		
		<a title="<?php echo gtext("ruota in senso orario");?>" class="a_rotate_o" href="<?php echo $this->baseUrl.'/immaginiarchivi/rotateo/'.$records[$i]['immagini_archivi']['id_immagine_archivio'];?>?csrf=<?php echo User::$csrfToken;?>"><i class="fa fa-repeat" aria-hidden="true"></i></a>
	</div>
	
	<div class="box_thumb_down">
		<div class="row">
			<div class="col-md-2">
				<img src="<?php echo $this->baseUrl.'/thumb/archivio/'.$records[$i]['immagini_archivi']['immagine']."/".rand(1,999999);?>">
			</div>
			<div class="col-md-10">
				<b><?php echo gtext("Nome file");?></b>: <?php echo $records[$i]['immagini_archivi']['immagine'] ? $records[$i]['immagini_archivi']['immagine'] : "--";?><br />
				<b><?php echo gtext("Alt tag");?></b>: <?php echo $records[$i]['immagini_archivi']['alt_tag'] ? $records[$i]['immagini_archivi']['alt_tag'] : "--";?>
				<?php if ($records[$i]['immagini_archivi']['id_immagine_tipologia']) { ?>
				<br /><b><?php echo gtext("Tipologia");?></b>: <?php echo ImmaginitipologieModel::sTitolo($records[$i]['immagini_archivi']['id_immagine_tipologia']);?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php } ?>
