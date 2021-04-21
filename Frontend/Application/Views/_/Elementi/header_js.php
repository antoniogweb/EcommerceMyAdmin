<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script>
	var baseUrl = "<?php echo $this->baseUrl;?>";
	var variante_non_esistente = "<?php echo gtext("Non esiste il prodotto con la combinazione di varianti selezionate", false);?>";
	var errore_combinazione = "<?php echo gtext("Si prega di indicare:", false);?>";
	var errore_quantita_minore_zero = "<?php echo gtext("Si prega di indicare una quantità maggiore di zero", false);?>";
	var errore_selezionare_variante = "<?php echo gtext("Si prega di selezionare la variante del prodotto", false);?>";
	var stringa_errore_giacenza_carrello = "<?php echo gtext("Attenzione, controllare la quantità delle righe evidenziate", false);?>";
	var back_cart_error = "red";
	var isMobile = <?php echo User::$isMobile ? "true" : "false";?>;
	var nazioniConVat = ['<?php echo implode("','",NazioniModel::elencoNazioniConVat())?>'];
	var pixel = <?php echo v("codice_fbk") ? "true" : "false"; ?>;
	var debug_js = <?php echo v("debug_js") ? "true" : "false"; ?>;
</script>

<script src="<?php echo $this->baseUrlSrc.'/Public/Js/';?>jquery-3.5.1.min.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/Public/Js/';?>ajaxQueue.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/Public/Js/';?>functions.js?v=<?php echo rand(1,10000);?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/Public/Js/';?>cart.js?v=<?php echo rand(1,10000);?>"></script>

<script src="<?php echo $this->baseUrlSrc."/Public/Js/uikit/"?>uikit.min.js"></script>
<script src="<?php echo $this->baseUrlSrc."/Public/Js/uikit/"?>uikit-icons.min.js"></script>

<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/Public/Js/icheck.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/Public/Js/image-picker/image-picker.min.js'></script>
