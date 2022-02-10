<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile",false);?> <?php echo $contatto["nome"];?>,
<?php echo gtext("ha ricevuto questa mail perché ha lasciato i propri contatti sul nostro sito web")?>
</p>

<p><?php echo gtext("Per confermare i suoi dati e attivare il suo contatto segua il seguente link, che sarà attivo per ".(v("tempo_conferma_uid_contatto")/3600)." ore.", false);?><br /><b><a href="<?php echo Domain::$publicUrl."/".$contatto["lingua"]."/conferma-contatto/".$contatto["uid_contatto"];?>"><?php echo gtext("Conferma e attiva il mio contatto");?></a></b></p>

<p><?php echo gtext("Cordiali saluti", false);?>.</p> 
