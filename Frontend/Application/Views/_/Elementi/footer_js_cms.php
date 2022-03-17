<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script>
	var baseUrl = "<?php echo $this->baseUrl;?>";
	var baseUrlSrc = "<?php echo $this->baseUrlSrc;?>";
	var variante_non_esistente = "<?php echo gtext("Non esiste il prodotto con la combinazione di varianti selezionate", false);?>";
	var errore_combinazione = "<?php echo gtext("Si prega di indicare:", false);?>";
	var errore_quantita_minore_zero = "<?php echo gtext("Si prega di indicare una quantità maggiore di zero", false);?>";
	var errore_selezionare_variante = "<?php echo gtext("Si prega di selezionare la variante del prodotto", false);?>";
	var variante_non_disponibile = "<?php echo gtext("Prodotto non disponibile", false);?>";
	var stringa_errore_giacenza_carrello = "<?php echo gtext("Attenzione, controllare la quantità delle righe evidenziate", false);?>";
	var back_cart_error = "red";
	var isMobile = <?php echo User::$isMobile ? "true" : "false";?>;
	var nazioniConVat = ['<?php echo implode("','",NazioniModel::elencoNazioniConVat())?>'];
	var pixel = <?php echo v("codice_fbk") ? "true" : "false"; ?>;
	var gtm_analytics = <?php echo v("codice_gtm_analytics") ? "true" : "false"; ?>;
	var debug_js = <?php echo v("debug_js") ? "true" : "false"; ?>;
	var input_border_color = "#e5e5e5";
	var attiva_spedizione = <?php echo v("attiva_spedizione") ? "true" : "false";?>;
	var check_giacenza = <?php echo v("attiva_giacenza") ? "true" : "false";?>;
	var carrello_monoprodotto = <?php echo v("carrello_monoprodotto") ? "true" : "false";?>;
	var mostra_errori_personalizzazione = <?php echo v("mostra_errori_personalizzazione") ? "true" : "false";?>;
	var coupon_ajax = <?php echo v("coupon_ajax") ? "true" : "false"; ?>;
	var codice_fiscale_obbligatorio_solo_se_fattura = <?php echo v("codice_fiscale_obbligatorio_solo_se_fattura") ? "true" : "false";?>;
</script>

<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>jquery-3.5.1.min.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>ajaxQueue.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>functions.js?v=<?php echo rand(1,10000);?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>cart.js?v=<?php echo rand(1,10000);?>"></script>

<script src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit-icons.min.js"></script>

<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/icheck.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.min.js'></script>

<?php include(tpf("/Elementi/fbk.php"));?>

<?php if ($this->controller == "home" && $this->action == "index" && isset($modali_frontend) && count($modali_frontend) > 0) { ?>
<?php include(tpf("/Elementi/modali.php"));?>
<?php } ?>

<?php if (isset($tipoPagina) && $tipoPagina == "FORM_FEEDBACK") { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/star-rating-svg-master/src/';?>jquery.star-rating-svg.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>rating.js?v=<?php echo rand(1,10000);?>"></script>
<?php } ?>

<?php if (CaptchaModel::getModulo()->inPage() && CaptchaModel::getModulo()->pathJs()) { ?>
<?php include(CaptchaModel::getModulo()->pathJs());?>
<?php } ?>

<?php include(tpf("/Elementi/footer_js_admin_panel.php"));?>
