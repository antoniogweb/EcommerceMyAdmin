<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


<div id="ext_login">
	
	<form action = '<?php echo $action;?>' method = 'POST' class="form-signin" role="form">
	<h4 class="form-signin-heading"><?php echo gtext("Per completare l'accesso, digita nel campo sottostante il codice a ".v("autenticazione_due_fattori_numero_cifre_admin")." cifre che ti è stato inviato via mail all'indirizzo");?> <b><?php echo partialliHideEmail($user["email"]);?></b></h4>
	<br />
		<input class="form-control" name='codice' type="text" autofocus="" placeholder="<?php echo gtext("Codice");?>">
		<br />
		<button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo gtext("Invia");?></button>
	</form>
</div>
