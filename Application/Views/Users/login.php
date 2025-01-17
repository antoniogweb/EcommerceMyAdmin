<?php if (!defined('EG')) die('Direct access not allowed!'); ?>


<div id="ext_login">
	<form action = '<?php echo $action;?>' method = 'POST' class="form-signin" role="form">
		<?php echo $notice; ?>
		<h3 class="form-signin-heading">Esegui il login</h3>
		<input class="form-control" name='username' type="text" autofocus="" placeholder="Username">
		<input class="form-control" name='password' type="password" placeholder="Password">
		
		<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
	</form>
</div>
