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
	var stringa_errore_righe_carrello = "<?php echo gtext("Attenzione, controllare i campi evidenziati", false);?>";
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
	var attiva_gift_card = <?php echo v("attiva_gift_card") ? "true" : "false"; ?>;
	var current_url = "<?php echo isset($currUrl) ? sanitizeHtml($currUrl) : "";?>";
	var filtro_prezzo_slider = <?php echo v("filtro_prezzo_slider") ? "true" : "false"; ?>;
	var versione_google_analytics = <?php echo v("versione_google_analytics"); ?>;
	var spesa_pagamento_possibile = <?php echo (PagamentiModel::getMaxPagamento() > 0) ? "true" : "false"; ?>;
	var stringa_errore_lista_non_selezionata = "<?php echo gtext("Si prega di selezionare una lista regalo");?>";
	var url_autenticati = "<?php echo VariabiliModel::paginaAutenticazione();?>";
	<?php if (v("attiva_liste_regalo")) { ?>
	var stringa_testo_copiato_clipboard = "<?php echo gtext("Il link della lista è stato copiato negli appunti.");?>";
	<?php } ?>
	var attiva_icheck = <?php echo (!isset($skipIcheck)) ?  "true" : "false";  ?>
</script>
<?php if (!isset($skipJquery)) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>jquery-3.5.1.min.js"></script>
<?php } ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>ajaxQueue.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>cms.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>functions.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>

<?php if ($this->controller == "listeregalo" || (isset($fsection) && $fsection == "prodotti") || isset($loadJsListe)) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>listeregalo.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/';?>jquery-ui.min.js"></script>
<?php if (file_exists(ROOT.'/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/main/ui/i18n/datepicker-'.Params::$lang.'.js')) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/jquery-ui-1.13.2.custom/main/ui/i18n/datepicker-'.Params::$lang.'.js';?>"></script>
<?php } ?>
<?php } ?>

<?php if (v("ecommerce_attivo")) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>cart.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>

<script defer src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit-icons.min.js"></script>
<?php if (!isset($skipIcheck)) { ?>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/icheck.min.js'></script>
<?php } ?>
<?php if (v("ecommerce_attivo")) { ?>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.min.js'></script>
<?php } ?>

<?php if (v("filtro_prezzo_slider")) { ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/jquery-nstslider-master/dist/';?>jquery.nstSlider.min.js"></script>
<?php } ?>

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

<?php if (App::$isUsingCrud) { ?>
<script>
	var checkbox_event = 'ifChanged';
	var doYouConfirmString = "<?php echo gtext("Confermi l'azione: ")?>";
	var noSelectedString = "<?php echo gtext("Si prega di selezionare alcune righe")?>";
	var stringaConfermiEliminazione = "<?php echo gtext("Confermi l'eliminazione dell'elemento?")?>";
</script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>crud.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Public/Js/';?>crud.js?v=<?php echo v("usa_versione_random") ? rand(1,10000): 1;?>"></script>
<?php } ?>

<?php include(tpf("/Elementi/footer_js_admin_panel.php"));?>
