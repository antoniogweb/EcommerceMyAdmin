<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile [NOME_CLIENTE]",false);?>,</p>

<p><?php echo gtext("ecco il codice di verifica a ".v("conferma_registrazione_numero_cifre_codice_verifica"). " cifre necessario per completare la conferma dell'account nel nostro sito web:");?></p>

<h2 style="text-align:center;">[CODICE_VERIFICA]</h2>

<p><?php echo gtext("Se ha ricevuto questa e-mail per errore le chiediamo gentilmente di cancellarla");?>.</p>
