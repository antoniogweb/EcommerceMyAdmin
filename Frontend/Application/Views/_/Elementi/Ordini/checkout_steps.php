<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (!isset($textClassAutenticazione))
	$textClassAutenticazione = "";

if (!isset($textClassCheckout))
	$textClassCheckout = "";

if (!isset($classBadgeAutenticazione))
	$classBadgeAutenticazione = "";

if (!isset($classBadgeCheckout))
	$classBadgeCheckout = "";
?>
<div class="uk-child-width-1-2@m uk-text-center uk-flex uk-flex-center uk-margin-large-bottom">
	<div>
		<progress id="js-progressbar" class="uk_progress uk-progress uk-margin-remove" value="<?php echo $percentuale;?>" max="100"></progress>
		<div style="margin-top:-13px;" class="uk-grid uk-grid-collapse uk-text-center uk-child-width-expand" uk-grid>
			<div>
				<a href="<?php echo $this->baseUrl."/autenticazione";?>"><span class="uk-badge <?php echo $classBadgeAutenticazione;?>">1</span><br /><span class="uk-text-meta <?php echo $textClassAutenticazione;?>"><?php echo gtext("Autenticazione");?></span></a>
			</div>
			<div>
				<span class="uk-badge <?php echo $classBadgeCheckout;?>">2</span><br /><span class="uk-text-meta <?php echo $textClassCheckout;?>"><?php echo gtext("Checkout");?></span>
			</div>
		</div>
	</div>
</div>
