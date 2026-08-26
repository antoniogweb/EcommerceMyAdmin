<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtextPlain("Gentile",false);?> <?php echo $contatto["nome"];?>,
<?php echo gtextPlain("ha ricevuto questa mail perché ha lasciato i propri contatti sul nostro sito web")?>
</p>

<p><?php echo gtextPlain("Per confermare i suoi dati e attivare il suo contatto segua il seguente link, che sarà attivo per ".(v("tempo_conferma_uid_contatto")/3600)." ore.", false);?><br /><b><a href="<?php echo Domain::$publicUrl."/".$contatto["lingua"]."/conferma-contatto/".$contatto["uid_contatto"];?>"><?php echo gtextPlain("Conferma e attiva il mio contatto");?></a></b></p>

<p><?php echo gtextPlain("Cordiali saluti", false);?>.</p> 
