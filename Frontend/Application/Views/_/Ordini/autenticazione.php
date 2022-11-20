<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Carrello") => $this->baseUrl."/carrello/vedi",
	gtext("Checkout") => "",
);
$titoloPagina = gtext("Checkout");

include(tpf("/Elementi/Pagine/page_top.php")); ?>

<?php
$widthPulsante = "uk-width-1-1 uk-width-2-3@s";
$noLoginNotice = true;
$action = $this->baseUrl."/regusers/login?redirect=/checkout";
RegusersModel::$redirectQueryString = "redirect=checkout";

$percentuale = 0;
$textClassAutenticazione = "uk-text-secondary";
$classBadgeCheckout = "uk_badge_meta";
if (!User::$isMobile)
	include(tpf("/Elementi/Ordini/checkout_steps.php"));
?>

<div class="uk-child-width-expand@s uk-text-left uk-grid-divider uk-grid uk-grid-column-large" uk-grid>
	<div class="uk-width-1-2@m uk-text-left">
		<div class="uk-margin-medium-top uk-margin-medium-bottom">
			<h3><?php echo gtext("Accedi");?></h3>
			<div class="uk-text-meta"><?php echo gtext("Inserisci Username e Password per continuare come utente loggato.");?><br /><br /></div>
			
			<?php
// 			$nascondiPlaceholder = true;
			include(tpf(ElementitemaModel::p("LOGIN_FORM","", array(
				"titolo"	=>	"Form login",
				"percorso"	=>	"Elementi/Generali/LoginForm",
			))));
			?>
			
			<?php
			ElementitemaModel::$percorsi["LOGIN_PASSWORD"]["nome_file"] = "default";
			include(tpf(ElementitemaModel::p("LOGIN_PASSWORD","", array(
				"titolo"	=>	"Link al recupero password",
				"percorso"	=>	"Elementi/Generali/LoginPassword",
			))));
			?>
			
			<?php
			if (!VariabiliModel::confermaUtenteRichiesta() && v("abilita_login_tramite_app"))
				include(tpf(ElementitemaModel::p("LOGIN_APP","", array(
					"titolo"	=>	"Pulsanti di login app esterne",
					"percorso"	=>	"Elementi/Generali/LoginApp",
				))));
			?>
		</div>
	</div>
	<div class="uk-width-1-2@m uk-text-left">
		<div class="uk-margin-medium-top uk-margin-bottom">
			<h3><?php echo gtext("Continua come ospite o crea un account");?></h3>
			<div class="uk-text-meta"><?php echo gtext("Continua l'acquisto inserendo i tuoi dati. Se lo desideri, potrai creare un account in fase di checkout.");?></div>
		</div>
		
		<form class="" action = '<?php echo $this->baseUrl."/checkout";?>' method = 'GET'>
			<fieldset class="uk-fieldset">
				<div class="uk-margin">
					<label class="uk-form-label uk-text-bold"><?php echo gtext("Indirizzo e-mail");?></label>
					<div class="uk-form-controls">
						<input class="uk-input uk-width-1-2@s uk-width-1-1@m" name="default_email" type="text" placeholder="<?php echo gtext("Scrivi qui il tuo indirizzo e-mail")?>"/>
					</div>
				</div>
				
				<div>
					<div class="uk-button uk-button-primary uk-width-1-1 spinner uk-hidden" uk-spinner="ratio: .70"></div>
					<input autocomplete="new-password" class="uk-button uk-button-primary uk-width-1-1 btn_submit_form" type="submit" name="login" value="<?php echo gtext("Procedi come ospite");?>" />
				</div>
			</fieldset>
		</form>
	</div>
</div>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));
