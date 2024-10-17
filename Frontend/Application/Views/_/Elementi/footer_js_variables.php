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
	var attiva_icheck = <?php echo (!isset($skipIcheck)) ?  "true" : "false";  ?>;
	var motore_ricerca = "<?php echo strtolower(MotoriricercaModel::getCodiceAttivo());?>";
	<?php if (v("attiva_controllo_robustezza_password")) { ?>
	var password_regular_expression_caratteri_maiuscoli = "<?php echo v("password_regular_expression_caratteri_maiuscoli");?>";
	var password_regular_expression_caratteri_minuscoli = "<?php echo v("password_regular_expression_caratteri_minuscoli");?>";
	var password_regular_expression_caratteri_numerici = "<?php echo v("password_regular_expression_caratteri_numerici");?>";
	var password_regular_expression_caratteri_speciali = "<?php echo v("password_regular_expression_caratteri_speciali");?>";
	var password_regular_expression_numero_caratteri = <?php echo v("password_regular_expression_numero_caratteri");?>;
	<?php } ?>
</script>
<?php $jsVariablesLoaded = true;?>
