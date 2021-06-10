<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo Form::$notice;?>
<form action="<?php echo Domain::$currentUrl;?>#contatti-form" method="post" novalidate="novalidate">
	<fieldset class="uk-fieldset uk-width-2-3@m uk-width-1-1@s">
		<div uk-grid class="uk-margin"> 
			<div class="uk-width-1-2@m uk-width-1-2@s">
				<?php echo Html_Form::input("nome",Form::$values["nome"],"uk-input class_nome","nome","placeholder='".gtext("Nome*")."'");?>
			</div>
			
			<div class="uk-width-1-2@m uk-width-1-2@s">
				<?php echo Html_Form::input("email",Form::$values["email"],"uk-input class_email","email","placeholder='".gtext("Email*")."'");?>
			</div>
		</div>

		<div class="uk-margin">
			<?php echo Html_Form::textarea("messaggio",Form::$values["messaggio"],"uk-textarea class_messaggio","messaggio","rows='5' placeholder='".gtext("Messsaggio*")."'");?>
		</div>
		
		<?php include (tpf("Elementi/Pagine/campo-captcha.php"));?>
		
		<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid condizioni_privacy_box class_accetto">
			<?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
			<label><?php echo Html_Form::checkbox('accetto',Form::$values["accetto"],'1','uk-checkbox');?> <?php echo gtext("Ho letto e accetto le condizione della");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></label>
		</div>
		
		<?php echo Html_Form::hidden("invia","invia");?>
		
		<div class="uk-margin">
			<button type='submit' class="uk-button uk-button-default background-yellow color-white"><?php echo gtext('Invia',false);?></button>
		</div>
	</fieldset>
</form>
