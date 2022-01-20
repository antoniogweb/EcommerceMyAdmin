<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="box_form_evidenzia">
	<?php
	echo FeedbackModel::$sNotice;
	?>
	<form action="<?php echo Domain::$currentUrl;?>#contatti-form" method="post" novalidate="novalidate">
		<fieldset class="uk-fieldset">
			<div uk-grid class="uk-margin"> 
				<div class="uk-width-1-2@m uk-width-1-2@s">
					<?php echo Html_Form::input("autore",FeedbackModel::gValue("autore"),"uk-input class_autore","autore","placeholder='".gtext("Il tuo nome*")."'");?>
				</div>
				
				<div class="uk-width-1-2@m uk-width-1-2@s">
					<?php echo Html_Form::input("email",FeedbackModel::gValue("email"),"uk-input class_email","email","placeholder='".gtext("Email*")."'");?>
				</div>
			</div>

			<div class="uk-margin">
				<?php echo Html_Form::textarea("testo",FeedbackModel::gValue("testo"),"uk-textarea class_testo","testo","rows='5' placeholder='".gtext("Il tuo commento*")."'");?>
			</div>
			
			<?php include (tpf("Elementi/Pagine/campo-captcha.php"));?>
			
			<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
				<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
				<label><?php echo Html_Form::checkbox('accetto',FeedbackModel::gValue("accetto"),'1','uk-checkbox');?> <?php echo gtext("Ho letto e accetto le condizione della");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></label>
			</div>
			
			<?php echo Html_Form::hidden("inviaFeedback","inviaFeedback");?>
			
			<div class="uk-margin">
				<button type='submit' class="uk-button uk-button-secondary background-yellow color-white"><?php echo gtext('Invia',false);?></button>
			</div>
		</fieldset>
	</form>
</div> 
