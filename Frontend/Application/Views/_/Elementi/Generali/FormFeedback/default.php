<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="box_form_evidenzia">
	<div uk-grid class="uk-margin uk-grid">
		<div class="uk-width-1-2@m uk-width-1-2@s">
			<?php $datiPrototto = FeedbackModel::gDatiProdotto();?>
			<h2><?php echo field($datiPrototto, "title");?></h2>
			
			<img src="<?php echo $this->baseUrlSrc."/thumb/dettagliobig/".$datiPrototto["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($datiPrototto, "title"));?>" />
			
			<div class="uk-margin">
				<a href="<?php echo Url::getRoot().getUrlAlias(field($datiPrototto, "id_page"));?>" class="uk-button uk-button-primary uk-button-small"><span uk-icon="icon: arrow-left"></span> <?php echo gtext("Torna al prodotto");?></a>
			</div>
		</div>
		<div id="form-feedback" class="uk-width-1-2@m uk-width-1-2@s">
			<h4 class="uk-margin"><?php echo t("Scrivi la tua valutazione");?></h4>
			<div class="uk-margin uk-text-meta">
				<?php echo t("Descrivi la tua esperienza con questo prodotto.");?> <span class="uk-text-emphasis"><?php echo t("Le valutazioni verranno lette e pubblicate sul sito.");?></span>
			</div>
			
			<?php if (!v("feedback_solo_se_loggato") || User::$logged) { ?>
			<?php echo FeedbackModel::$sNotice; ?>
			<form action="<?php echo Domain::$currentUrl."?".v("var_query_string_id_rif")."=".(int)FeedbackModel::gIdProdotto();?>#form-feedback" method="post" novalidate="novalidate">
				<fieldset class="uk-fieldset">
					<div class="my-rating class_voto"></div>
					
					<div class="uk-margin"> 
						<?php echo Html_Form::input("autore",FeedbackModel::gValue("autore"),"uk-input class_autore","autore","placeholder='".gtext("Il tuo nome*")."'");?>
					</div>
					
					<?php if (!User::$id) { ?>
					<div class="uk-margin"> 
						<?php echo Html_Form::input("email",FeedbackModel::gValue("email"),"uk-input class_email","email","placeholder='".gtext("Email*")."'");?>
					</div>
					<?php } ?>
					
					<div class="uk-margin">
						<?php echo Html_Form::textarea("testo",FeedbackModel::gValue("testo"),"uk-textarea class_testo","testo","rows='5' placeholder='".gtext("Il tuo commento*")."'");?>
					</div>
					
					<?php include (tpf("Elementi/Pagine/campo-captcha.php"));?>
					
					<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
						<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
						<label>
							<?php echo Html_Form::checkbox('accetto',FeedbackModel::gValue("accetto"),'1','uk-checkbox');?> <span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></span></a>
						</label>
					</div>
					
					<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto_feedback">
						<?php $idCondizioniFeedback = PagineModel::gTipoPagina("CONDIZIONI_FEEDBACK"); ?>
						<label>
							<?php echo Html_Form::checkbox('accetto_feedback',FeedbackModel::gValue("accetto_feedback"),'1','uk-checkbox');?>
							<?php if ($idCondizioniFeedback) { ?>
							<span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idCondizioniFeedback);?>"><?php echo gtext("condizioni di pubblicazione della mia valutazione");?></span></a>
							<?php } else { ?>
							<div class="uk-alert uk-alert-danger"><?php echo gtext("Attenzione, condizioni di pubblicazioni del feedback assenti");?></div>
							<?php } ?>
						</label>
					</div>
					
					<?php echo Html_Form::hidden("inviaFeedback","inviaFeedback");?>
					
					<?php echo Html_Form::hidden("voto",FeedbackModel::gValue("voto"));?>
					
					<div class="uk-margin">
						<div class="uk-button uk-button-secondary spinner uk-hidden" uk-spinner="ratio: .70"></div>
						<button type='submit' class="uk-button uk-button-secondary btn_submit_form"><?php echo gtext('Invia',false);?></button>
					</div>
				</fieldset>
			</form>
			<?php } else { ?>
			<?php
			$notice = null;
			$redirectQueryString = "?redirect=".PagesModel::$currentIdPage."-".FeedbackModel::$idProdotto;
			$action = Url::getRoot("regusers/login".$redirectQueryString);
			?>
			<div class="uk-text-center">
			<?php include(tpf(ElementitemaModel::p("FORM_LOGIN","", array(
				"titolo"	=>	"Login form",
				"percorso"	=>	"Elementi/Generali/Login",
			))));
			?>
			</div>
			<?php } ?>
		</div>
	</div>
</div> 
