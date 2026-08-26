<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Area riservata")	=>	$this->baseUrl."/".Url::routeToUrl("area-riservata"),
	gtextPlain("Condizioni di privacy") => "",
);

$titoloPagina = gtextPlain("Condizioni di privacy");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "privacy";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>

<?php echo flash("noticecookies");?>
<?php if (isset($_COOKIE["ok_cookie"])) { ?>
<h2 id="privacy"><?php echo gtextPlain("Le tue preferenze sui cookie");?></h2>
<div class="blocco_coupon">
	<div class="uk-overflow-auto">
		<table class="uk-table uk-table-divider uk-table-small">
			<tr>
				<th><?php echo gtextPlain("Tipologia cookie");?></th>
				<th><?php echo gtextPlain("Stato");?></th>
				<th></th>
			</tr>
			<tr>
				<td><?php echo gtextPlain("Tecnici");?></td>
				<td><span class="uk-text-success"><span uk-icon="check"></span> <?php echo gtextPlain("attivi");?></span></td>
				<td></td>
			</tr>
			<tr>
				<td><?php echo gtextPlain("Statistiche + Marketing");?></td>
				<?php if (isset($_COOKIE["ok_cookie_terzi"])) { ?>
				<td>
					<span class="uk-text-success"><span uk-icon="check"></span> <?php echo gtextPlain("attivi");?></span>
				</td>
				<td class="uk-text-right">
					<a class="uk-text-small uk-text-danger" href="<?php echo $this->baseUrl."/riservata/privacy?cancella_cookies"?>"><i class="fa fa-trash"></i> <?php echo gtextPlain("revoca l'approvazione");?></a>
				</td>
				<?php } else { ?>
				<td>
					<span class="uk-text-danger"><span uk-icon="ban"></span> <?php echo gtextPlain("non attivi");?></span>
				</td>
				<td></td>
				<?php } ?>
			</tr>
		</table>
	</div>
	<?php $idCookies = PagineModel::gTipoPagina("COOKIE"); ?>
	<?php if ($idCookies) { ?>
	<div class="uk-margin">
		<a href="<?php echo $this->baseUrl."/".getUrlAlias($idCookies);?>"><?php echo gtextPlain("Leggi l'informativa sui cookie");?></a>
	</div>
	<?php } ?>
</div>
<?php } ?>

<?php if (v("permetti_eliminazione_account")) { ?>
<h2><?php echo gtextPlain("Cancella account");?></h2>
<div class="uk-text-center">
	<?php echo $notice; ?>
</div>
<div class="blocco_coupon">
	<div class="uk-text-meta uk-margin-bottom">
		<?php echo testo("Per cancellare l'account è necessario inserire la password e confermare tramite il form sottostante.");?>
	</div>
	<form class="" action="<?php echo $this->baseUrl."/riservata/privacy";?>#privacy" method="POST">
		<div class="uk-margin">
			<label class="uk-form-label"><?php echo gtextPlain("Password");?></label>
			<div class="uk-form-controls">
				<?php echo Html_Form::password("password","","uk-input class_password",null,"placeholder='".gtextAttr("Inserisci la password", false)."'");?>
			</div>
		</div>
		
		<input type="submit" class="<?php echo v("classe_pulsanti_submit");?>" name="cancella" value="<?php echo gtextAttr("Cancella account", false);?>">
	</form>
</div>
<?php } ?>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
