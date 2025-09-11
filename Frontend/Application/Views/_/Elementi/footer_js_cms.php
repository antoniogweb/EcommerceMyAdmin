<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (!isset($jsVariablesLoaded)) { ?>
	<?php include(tpf("/Elementi/footer_js_variables.php"));?>
<?php } ?>
<?php if (!isset($skipJquery)) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>jquery-3.5.1.min.js"></script>
<?php } ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>ajaxQueue.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>cms.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>functions.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php if (v("attiva_controllo_robustezza_password")) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>password.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>

<?php if ($this->controller == "listeregalo" || (isset($fsection) && $fsection == "prodotti") || isset($loadJsListe) || isset($loadJqueryUi)) { ?>
	<?php if (v("attiva_liste_regalo")) { ?>
	<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>listeregalo.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
	<?php } ?>
	<?php if (!isset($skipJqueryUi)) { ?>
		<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/';?>jquery-ui.min.js"></script>
		<?php if (file_exists(ROOT.'/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/main/ui/i18n/datepicker-'.Params::$lang.'.js')) { ?>
		<script type="text/javascript" src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/main/ui/i18n/datepicker-'.Params::$lang.'.js';?>"></script>
		<?php } ?>
	<?php } ?>
<?php } ?>

<?php if ($this->controller == "ticket") { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>ticket.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>

<?php if ($this->controller == "promozioni") { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>promozioni.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>

<?php if (v("ecommerce_attivo")) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>cart.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>
<?php if (!isset($skipUikitIcons)) { ?>
<script <?php if (v("usa_defear")) { ?>defer<?php } ?> src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit-icons.min.js"></script>
<?php } ?>
<?php if (!isset($skipIcheck)) { ?>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/icheck.min.js'></script>
<?php } ?>
<?php if (v("ecommerce_attivo")) { ?>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.min.js'></script>
<?php } ?>

<?php if (v("filtro_prezzo_slider")) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/jquery-nstslider-master/dist/';?>jquery.nstSlider.min.js"></script>
<?php } ?>

<?php
if (!isset($stringaCacheMeta))
	$stringaCacheMeta = "";
include(tpf("/Elementi/fbk.php",false, false, $stringaCacheMeta));?>

<?php if ($this->controller == "home" && $this->action == "index") { ?>
<?php include(tpf("/Elementi/modali.php",false,false));?>
<?php } ?>

<?php if (isset($tipoPagina) && $tipoPagina == "FORM_FEEDBACK") { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/star-rating-svg-master/src/';?>jquery.star-rating-svg.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/Minified/';?>rating.min.js?v=<?php echo rand(1,10000);?>"></script>
<?php } ?>

<?php if (CaptchaModel::getModulo()->inPage() && CaptchaModel::getModulo()->pathJs()) { ?>
<?php include(CaptchaModel::getModulo()->pathJs());?>
<?php } ?>

<?php if (App::$isUsingCrud) { ?>
<script>
	var checkbox_event = 'ifChanged';
	var doYouConfirmString = "<?php echo gtext("Confermi l'azione: ")?>";
	var noSelectedString = "<?php echo gtext("Si prega di selezionare alcune righe")?>";
	var stringaConfermiEliminazione = "<?php echo gtext("Confermi l'eliminazione dell'elemento?")?>";
</script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>crud.min.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Public/Js/';?>crud.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>

<?php include(tpf("/Elementi/footer_js_admin_panel.php"));?>
