<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Le mie valutazioni") => "",
);

$titoloPagina = gtext("Le mie valutazioni");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "feedback";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<div class="box_feedback">
	<?php if (isset($user_feedback) && count($user_feedback) > 0) { ?>
		<?php foreach ($user_feedback as $pf) {
			$idPage = $pf["feedback"]["id_page"];
			
			$pageDetails = PagesModel::getPageDetails((int)$idPage);
		?>
			<?php include(tpf("/Elementi/Categorie/feedback.php")); ?>
			<div class="uk-margin-medium-bottom">
				<span class="uk-text-small uk-text-meta"><?php echo gtext("Valutazione inserita nel prodotto:")?></span> <a href="<?php echo $this->baseUrl."/".getUrlAlias((int)$idPage);?>"><span class="uk-text-emphasis uk-text-small"><?php echo field($pageDetails, "title");?></span></a><br />
				
				<?php echo FeedbackModel::gHtmlStatoFeedback($pf);?>
			</div>
		<?php } ?>
	<?php } else { ?>
		<?php echo gtext("Non hai lasciato alcuna valutazione");?>
	<?php } ?>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
