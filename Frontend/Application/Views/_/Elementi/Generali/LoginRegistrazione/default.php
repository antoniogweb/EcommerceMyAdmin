<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-margin uk-margin-large-top uk-text-small box_info_registrazione">
	<?php echo gtextPlain("Vuoi creare un nuovo account?")?> <a class="uk-text-meta" href="<?php echo $this->baseUrl."/".Url::routeToUrl("crea-account").$redirectQueryString;?>" class=""><?php echo gtextPlain("Registrati");?></a>
</div>
