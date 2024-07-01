<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php echo gtext("La password deve soddisfare i seguenti requisiti");?>:
<ul>
	<li><?php echo gtext("Deve essere lunga almeno 8 caratteri");?></li>
	<li><?php echo gtext("Deve avere almeno un carattere minuscolo [a - z]");?></li>
	<li><?php echo gtext("Deve avere almeno un carattere maiuscolo  [A - Z]");?></li>
	<li><?php echo gtext("Deve avere almeno un carattere numerico [0 - 9]");?></li>
	<li><?php echo gtext("Deve avere almeno un carattere speciale tra i seguenti: #?!@$%^*-");?></li>
</ul>
