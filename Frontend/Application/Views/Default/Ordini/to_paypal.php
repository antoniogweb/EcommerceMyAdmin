<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (strcmp($ordine["pagamento"],"paypal") === 0 and strcmp($ordine["stato"],"pending") === 0 and strcmp($tipoOutput,"web") === 0) { ?>

<div style="visibility:hidden;">
<?php echo $pulsantePaypal;?>
</div>
<script>document.paypal_form.submit();</script>

<?php } ?>

