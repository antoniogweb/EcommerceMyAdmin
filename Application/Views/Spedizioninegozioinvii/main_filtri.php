<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
	<?php foreach ($spedizionieri_attivi as $spedizioniere) { ?>
	<a title="<?php echo gtext("Prenota borderò")." ".$spedizioniere["titolo"];?>" role="button" class="btn btn-success menu_btn make_spinner" href="<?php echo $this->baseUrl;?>/spedizioninegozioinvii/prenota/<?php echo $spedizioniere["id_spedizioniere"].$this->viewStatus;?>"><i class="fa fa-book"></i> <?php echo gtext("Prenota borderò")." ".$spedizioniere["titolo"];?></a>
	<?php } ?>
</p>
