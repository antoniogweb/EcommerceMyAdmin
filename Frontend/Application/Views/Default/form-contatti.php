<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo Form::$notice;?>
<form action="<?php echo Domain::$currentUrl;?>#contatti-form" method="post" class="wpcf7-form" novalidate="novalidate">
	<p>
		<label> <?php echo gtext("Nome");?><span class="wpcf7-form-control-wrap your-name">
		<?php echo Html_Form::input("nome",Form::$values["nome"],"wpcf7-form-control wpcf7-text i_nome","nome","placeholder='Scrivi il tuo nome'");?>
		</label>
		
		<label> <?php echo gtext("E-mail");?><span class="wpcf7-form-control-wrap your-email">
		<?php echo Html_Form::input("email",Form::$values["email"],"wpcf7-form-control wpcf7-text i_mail","email","placeholder='Scrivi il tuo indirizzo e-mail'");?>
		</label>
		
		<?php /*echo Html_Form::input("cognome","","cognome",null);*/?>
		
		<label> <?php echo gtext("Messaggio");?><span class="wpcf7-form-control-wrap your-message">
		<?php echo Html_Form::textarea("messaggio",Form::$values["messaggio"],"wpcf7-form-control wpcf7-textarea form-messaggio","messaggio","placeholder='Scrivi il tuo messaggio'");?>
<!-- 		<textarea name="your-message" cols="40" rows="5" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="Enter text here..."></textarea></span> </label> -->
		
		<div class='condizioni_privacy_box'><span class='condizioni_privacy'>
			<?php echo gtext('PRIVACY: I dati inseriti saranno trattati ai sensi del DL 196/2003 dal soggetto incaricato');?> (<a href="<?php echo Domain::$name."/privacy.html";?>"><?php echo gtext("clicca qui");?></a>). <?php echo gtext('Autorizzo pertanto il trattamento dei dati da me comunicati')?></span><br />
			
			<?php echo gtext('Accetto');?>
			<?php echo Html_Form::checkbox('accetto',"",'1','i_check');?>
		</div>
		
		<input class='pulsante_invio' type='submit' name='invia' value='<?php echo gtext('Invia il messaggio',false);?>'>
	</p>
</form>
