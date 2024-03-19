<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div title='aggiungi elemento' class='aggiungi_blocco_testo' href='#'>
	<i class='fa fa-plus'></i>
	<div class="aggiungi_blocco_testo_context">
		<div url="/contenuti/aggiungitesto/<?php echo ContenutiModel::$idContenuto;?>/TESTO/DOPO" class="aggiungi_blocco_testo_context_element"><i class='fa fa-font'></i> <span>Aggiungi testo</span></div>
		<div url="/contenuti/aggiungitesto/<?php echo ContenutiModel::$idContenuto;?>/IMMAGINE/DOPO" class="aggiungi_blocco_testo_context_element"><i class='fa fa-image'></i> <span>Aggiungi immagine</span></div>
		<div url="/contenuti/aggiungitesto/<?php echo ContenutiModel::$idContenuto;?>/LINK/DOPO" class="aggiungi_blocco_testo_context_element"><i class='fa fa-link'></i> <span>Aggiungi link</span></div>
	</div>
</div><div title='elimina elemento'  url="/contenuti/eliminatesto/<?php echo ContenutiModel::$idContenuto;?>/<?php echo $clean["id"];?>" class='elimina_blocco_testo' href='#'>
	<i class='fa fa-trash'></i>
</div>
<div style="display:none;" class="testo-tag"><?php echo ContenutiModel::togliParentesiQuadre(str_replace("_".ContenutiModel::$idContenuto,"",$matches[0]));?></div>
