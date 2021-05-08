<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo Form::$notice;?>
<form action="<?php echo Domain::$currentUrl;?>#contatti-form" method="post" novalidate="novalidate">
	<fieldset class="uk-fieldset uk-width-2-3@m uk-width-1-1@s">
		<div uk-grid class="uk-margin"> 
			<div class="uk-width-1-2@m uk-width-1-2@s">
				<?php echo Html_Form::input("nome",Form::$values["nome"],"uk-input i_nome","nome","placeholder='".gtext("Nome*")."'");?>
			</div>
			
			<div class="uk-width-1-2@m uk-width-1-2@s">
				<?php echo Html_Form::input("email",Form::$values["email"],"uk-input i_mail","email","placeholder='".gtext("Email*")."'");?>
			</div>
		</div>

		<div class="uk-margin">
			<?php echo Html_Form::textarea("messaggio",Form::$values["messaggio"],"uk-textarea form-messaggio","messaggio","rows='5' placeholder='".gtext("Messsaggio*")."'");?>
		</div>

		<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box">
			<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
			<label><?php echo Html_Form::checkbox('accetto',"",'1','uk-checkbox i_check');?> <?php echo gtext("Ho letto e accetto le condizione della");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></label>
			
		</div>
		
		<div class="t">
			<?php echo Html_Form::input("cognome","","cognome",null);?>
		</div>
		
		<div class="uk-margin">
			<button type='submit' name='invia' class="uk-button uk-button-default background-yellow color-white"><?php echo gtext('Invia',false);?></button>
		</div>
	</fieldset>
</form>
