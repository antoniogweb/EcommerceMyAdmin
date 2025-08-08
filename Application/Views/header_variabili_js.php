<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script>
	var baseUrl = "<?php echo $this->baseUrl;?>";
	var baseUrlSrc = "<?php echo $this->baseUrlSrc;?>";
	var parentBaseUrl = "<?php echo $parentRoot;?>";
	var applicationName = "<?php echo $this->applicationUrl;?>";
	var controllerName = "<?php echo $this->controller;?>";
	var actionName = "<?php echo $this->action;?>";
	var viewStatus = "<?php echo $this->viewStatus;?>";
	var partial = <?php echo partial() ? "true" : "false";?>;
	var altezza_aggiuntiva_ricalcola_altezza_dialog = 0;
	var nazioniConProvince = ['<?php echo implode("','",NazioniModel::nazioniConProvince())?>'];
</script>
