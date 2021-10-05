<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Condizioni di privacy") => "",
);

$titoloPagina = gtext("Condizioni di privacy");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "privacy";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<?php echo $noticecookies;?>
<?php if (isset($_COOKIE["ok_cookie"])) { ?>
<h2 id="privacy"><?php echo gtext("Cookie");?></h2>
<div class="blocco_coupon">
	<?php echo testo("Cookies");?><br />
	<?php $idCookies = PagineModel::gTipoPagina("COOKIE"); ?>
	<?php if ($idCookies) { ?>
	<div class="uk-margin-bottom">
		<a style="font-weight:bold;" href="<?php echo $this->baseUrl."/cookies.html"?>"><?php echo gtext("Vedi l'informativa sui cookie");?></a>
	</div>
	<?php } ?>
	<div class="uk-margin">
		<a style="font-weight:bold;" href="<?php echo $this->baseUrl."/riservata/privacy?cancella_cookies"?>"><i class="fa fa-trash"></i> <?php echo gtext("Revoca l'approvazione all'utilizzo dei cookies");?></a>
	</div>
</div>
<?php } ?>


<h2><?php echo gtext("Cancella account");?></h2>
<div class="uk-text-center">
	<?php echo $notice; ?>
</div>
<div class="blocco_coupon">
	<div class="uk-text-meta uk-margin-bottom">
		<?php echo testo("Per cancellare l'account è necessario inserire la password e confermare tramite il form sottostante.");?>
	</div>
	<form class="" action="<?php echo $this->baseUrl."/riservata/privacy";?>#privacy" method="POST">
		<div class="uk-margin">
			<label class="uk-form-label"><?php echo gtext("Password");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::password("password","","uk-input class_password",null,"placeholder='".gtext("Inserisci la password", false)."'");?>
			</div>
		</div>
		
		<input type="submit" class="uk-button uk-button-secondary" name="cancella" value="<?php echo gtext("Cancella account", false);?>">
	</form>
</div>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
