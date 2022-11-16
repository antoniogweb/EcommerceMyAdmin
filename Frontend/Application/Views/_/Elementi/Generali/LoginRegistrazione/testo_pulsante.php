<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($redirectQueryString))
	$redirectQueryString = "";
?>
<h3><?php echo gtext("Registrati");?></h3>
<div class="uk-text-meta"><?php echo gtext("Registrati per poter gestire accedere all'area riservata e gestire i tuoi ordini.");?></div>
<div class="uk-margin">
	<div class="uk-width-1-1 uk-width-1-3@s <?php echo v("classe_pulsanti_submit");?> spinner uk-hidden" uk-spinner="ratio: .70"></div>
	<a class="uk-width-1-1 uk-width-1-3@s <?php echo v("classe_pulsanti_submit");?> box_info_registrazione btn_submit_form" href="<?php echo $this->baseUrl."/crea-account".$redirectQueryString;?>"><?php echo gtext("Registrati");?></a>
</div>
<br />
