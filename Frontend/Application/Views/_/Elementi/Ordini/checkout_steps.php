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

if (!isset($classCircle))
	$classCircle = "uk-light uk-background-secondary";

if (!isset($classBoxSteps))
	$classBoxSteps = "uk-margin-medium-bottom";

if (!isset($stickyAttributes))
	$stickyAttributes = 'uk-sticky="offset: 0;bottom: true;animation: uk-animation-slide-top;"';
?>
<?php if (!User::$logged || User::$isMobile) { ?>
<div class="checkout-steps <?php echo $classBoxSteps;?> <?php echo User::$isMobile ? "checkout-steps-mobile" : "";?>" <?php if ($this->action == "index" && User::$isMobile) { echo $stickyAttributes; } ?>>
		<div class="uk-child-width-1-2@m uk-text-center uk-flex uk-flex-center">
		<div>
			<progress id="js-progressbar" class="uk_progress uk-progress uk-margin-remove" value="0" max="100"></progress>
			<div style="margin-top:-16px;" class="uk-grid uk-grid-collapse uk-text-center uk-child-width-expand" uk-grid>
				<?php if (User::$isMobile) { ?>
				<div pos="fatturazione">
					<span class="checkout-step uk-border-circle <?php echo $classBadgeCheckout;?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/user.svg");?></span></span>
					<br />
					<?php if ($this->action == "index") { ?>
					<span class="nome_step uk-text-small <?php echo $textClassCheckout;?>"><?php echo gtext("Fatturazione");?></span>
					<?php } ?>
				</div>
				<?php if (v("attiva_spedizione")) { ?>
				<div pos="spedizione">
					<span class="checkout-step uk-border-circle uk_badge_meta"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/location.svg");?></span></span><br /><span class="uk-hidden nome_step uk-text-small uk-text-secondary"><?php echo gtext("Spedizione");?></span>
				</div>
				<?php } ?>
				<?php if (count(OrdiniModel::$pagamenti) > 1) { ?>
				<div pos="pagamento">
					<span class="checkout-step uk-border-circle uk_badge_meta"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/credit-card.svg");?></span></span><br /><span class="uk-hidden nome_step uk-text-small uk-text-secondary"><?php echo gtext("Pagamento");?></span>
				</div>
				<?php } ?>
				<?php if (count($corrieri) > 1 && v("attiva_spedizione")) { ?>
				<div pos="consegna">
					<span class="checkout-step uk-border-circle uk_badge_meta"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/clock.svg");?></span></span><br /><span class="uk-hidden nome_step uk-text-small uk-text-secondary"><?php echo gtext("Consegna");?></span>
				</div>
				<?php } ?>
				<div pos="carrello">
					<span class="checkout-step uk-border-circle uk_badge_meta"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/carrello.svg");?></span></span><br /><span class="uk-hidden nome_step uk-text-small uk-text-secondary"><?php echo gtext("Totali");?></span>
				</div>
				<div pos="conferma">
					<span class="checkout-step uk-border-circle uk_badge_meta"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/check.svg");?></span></span><br /><span class="uk-hidden nome_step uk-text-small uk-text-secondary"><?php echo gtext("Conferma");?></span>
				</div>
				<?php } else { ?>
				<div class="step_active">
					<a href="<?php echo $this->baseUrl."/autenticazione";?>"><span class="<?php echo $classCircle;?> uk-border-circle checkout-step <?php echo $classBadgeAutenticazione;?>">1</span><br /><span class="uk-visibile@m nome_step uk-text-meta <?php echo $textClassAutenticazione;?>"><?php echo gtext("Autenticazione");?></span></a>
				</div>
				<div>
					<span class="checkout-step uk-border-circle <?php echo $classBadgeCheckout;?>">2</span><br /><span class="uk-visibile@m nome_step uk-text-meta <?php echo $textClassCheckout;?>"><?php echo gtext("Checkout");?></span>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
