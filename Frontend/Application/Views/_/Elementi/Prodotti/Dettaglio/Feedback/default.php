<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h2 class="uk-text-lead uk-text-uppercase uk-margin-medium-bottom"><?php echo gtext("Valutazioni clienti");?></h2>

<div class="box_feedback">
	<?php if (count($page_feedback) > 0) { ?>
		<?php foreach ($page_feedback as $pf) {
			include(tpf("/Elementi/Categorie/feedback.php"));
		} ?>
	<?php } else { ?>
		<?php echo gtext("Non sono presenti valutazioni da parte dei clienti");?>
	<?php } ?>
</div>

<?php $idPaginaInserisciFeedback = PagineModel::gTipoPagina("FORM_FEEDBACK"); ?>
<?php if ($idPaginaInserisciFeedback && v("permetti_aggiunta_feedback")) {
	$queryStringIdComb = "";
	if (PagesModel::$IdCombinazione)
		$queryStringIdComb = "&".v("var_query_string_id_comb")."=".PagesModel::$IdCombinazione;
?>
<div class="uk-margin-medium-top">
	<a href="<?php echo $this->baseUrl."/".getUrlAlias($idPaginaInserisciFeedback)."?".v("var_query_string_id_rif")."=".$p["pages"]["id_page"].$queryStringIdComb;?>" class="uk-button uk-button-secondary"><span uk-icon="pencil"></span> <?php echo gtext("Inserisci valutazione");?></a>
</div>
<?php } ?>
