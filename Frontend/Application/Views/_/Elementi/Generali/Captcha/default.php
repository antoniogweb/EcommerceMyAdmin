<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo "<div class='".v("alert_error_class")."'>".gtext("Sembra che ci sia un problema con la verifica dell'antispam (CAPTCHA).")."<br />".gtext("Assicuratevi di avere Javascript attivo nel browser.")."<br />".gtext("Provate inoltre a non utilizzare la compilazione automatica suggerita dal browser.")."<br />".gtext("Nel caso stiate utilizzando un dispositivo Apple (iPhone/iPad/Mac) con sistema operativo iOS 16, disabilitate la verifica automatica dei CAPTCHA nelle impostazioni.")."<br />".gtext("Nel caso la problematica continuasse contattate il negozio al")." <b>".v("telefono_aziendale")."</b> ".gtext("o tramite l'indirizzo email")." <b>".v("email_aziendale")."</b></div>";?>