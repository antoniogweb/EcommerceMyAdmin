<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php echo gtext("La password deve soddisfare i seguenti requisiti");?>:
<?php if (isset($_POST["password"])) { ?>
<ul>
	<?php if (strlen($_POST["password"]) < v("password_regular_expression_numero_caratteri")) { ?>
	<li><?php echo gtext("Deve essere lunga almeno 8 caratteri");?></li>
	<?php } ?>
	<?php if (!preg_match('/^'.v("password_regular_expression_caratteri_minuscoli").'.{1,}$/',$_POST["password"])) {  ?>
	<li><?php echo gtext("Deve avere almeno un carattere minuscolo [a - z]");?></li>
	<?php } ?>
	<?php if (!preg_match('/^'.v("password_regular_expression_caratteri_maiuscoli").'.{1,}$/',$_POST["password"])) {  ?>
	<li><?php echo gtext("Deve avere almeno un carattere maiuscolo  [A - Z]");?></li>
	<?php } ?>
	<?php if (!preg_match('/^'.v("password_regular_expression_caratteri_numerici").'.{1,}$/',$_POST["password"])) {  ?>
	<li><?php echo gtext("Deve avere almeno un carattere numerico [0 - 9]");?></li>
	<?php } ?>
	<?php if (!preg_match('/^(?=.*?['.v("password_regular_expression_caratteri_speciali").']).{1,}$/',$_POST["password"])) {  ?>
	<li><?php echo gtext("Deve avere almeno un carattere speciale tra i seguenti:")." ".v("password_regular_expression_caratteri_speciali");?></li>
	<?php } ?>
</ul>
<?php } ?>
