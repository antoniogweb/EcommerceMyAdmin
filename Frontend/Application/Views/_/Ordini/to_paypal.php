<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($ordine["pagamento"],"paypal") === 0 and strcmp($ordine["stato"],"pending") === 0 and strcmp($tipoOutput,"web") === 0) { ?>
<!DOCTYPE html>
<html lang="<?php echo Params::$lang;?>">
<head>
	<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<style>
		input[type=submit]
		{
			background: #2c2c2c;
			color: #fff;
			border: 0px;
/* 			transition: all 0.5s ease; */
			padding: 12px 30px;
/* 			width: 100%; */
			display: inline-block;
			cursor: pointer;
			text-transform: uppercase;
		}
	</style>
</head>
<body>
	<?php if (v("mostra_pagina_intermedia_paypal")) { ?>
		<div style="text-align:center;margin-top:20px;">
			<div style="padding:20px;font-size:14px;">
				<?php echo gtext("Stai per essere reindirizzato al sito di Paypal per il pagamento.");?><br /><br />
				<?php echo gtext("Se non vieni reindirizzato entro 10 secondi, premi il pulsante per pagare.")?>
			</div>
			
			<?php echo $pulsantePaypal;?>
			
			<script>
			setTimeout(function(){
				document.paypal_form.submit();
			}, 500);
			</script>
		</div>
	<?php } else { ?>
		<div style="visibility:hidden;">
		<?php echo $pulsantePaypal;?>
		</div>
		<script>setTimeout(function(){
				document.paypal_form.submit();
			}, 100);
		</script>
	<?php } ?>
</body>
</html>
<?php } ?>
