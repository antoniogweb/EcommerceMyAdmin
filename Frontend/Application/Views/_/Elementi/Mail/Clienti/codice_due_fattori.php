<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile [NOME_CLIENTE]");?>,</p>

<p><?php echo gtext("ecco il codice di verifica a ".v("autenticazione_due_fattori_numero_cifre_admin"). " cifre necessario per completare l'autenticazione nel nostro sito web:");?></p>

<h2 style="text-align:center;">[CODICE]</h2>

<p><?php echo gtext("Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla");?>.</p> 
