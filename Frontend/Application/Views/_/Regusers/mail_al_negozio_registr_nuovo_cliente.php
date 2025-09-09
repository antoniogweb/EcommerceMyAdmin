<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>
<?php if ($this->registrazioneAgente) { ?>
<?php echo gtext("Un agente si è registrato nel vostro sito web.");?><br />
<?php echo gtext("Potete assegnare all'agente dei <b>codici coupon</b> dal pannello admin.");?><br />
<?php echo gtext("Ecco i suoi dati:");?>
<?php } else { ?>
<?php echo gtext("Un cliente si è registrato nel vostro sito web. Ecco i suoi dati:");?>
<?php } ?>
</p>

<?php if (v("gruppi_inseriti_da_approvare_alla_registrazione")) { ?>
<p><?php echo gtext("Può approvare il cliente nell'area admin, sotto la voce di menù E-commerce > Clienti > Da approvare."); ?></p>
<?php } ?>

USERNAME: <b><?php echo $clean["username"];?></b><br />
<?php if (strcmp($datiCliente["tipo_cliente"],"privato") === 0 || strcmp($datiCliente["tipo_cliente"],"libero_professionista") === 0) { ?>
<?php echo gtext("NOME");?>: <b><?php echo $datiCliente["nome"];?></b><br />
<?php echo gtext("COGNOME");?>: <b><?php echo $datiCliente["cognome"];?></b><br />
<?php } ?>
<?php if (strcmp($datiCliente["tipo_cliente"],"azienda") === 0) { ?>
<?php echo gtext("RAGIONE SOCIALE");?>: <b><?php echo $datiCliente["ragione_sociale"];?></b><br />
<?php } ?>
<?php if (strcmp($datiCliente["tipo_cliente"],"azienda") === 0 || strcmp($datiCliente["tipo_cliente"],"libero_professionista") === 0) { ?>
<?php echo gtext("PARTITA IVA");?>: <b><?php echo $datiCliente["p_iva"];?></b><br />
<?php } ?>
<?php echo gtext("CODICE FISCALE");?>: <b><?php echo $datiCliente["codice_fiscale"];?></b><br />
<?php echo gtext("INDIRIZZO");?>: <b><?php echo $datiCliente["indirizzo"];?></b><br />
<?php echo gtext("CAP");?>: <b><?php echo $datiCliente["cap"];?></b><br />
<?php echo gtext("NAZIONE");?>: <b><?php echo nomeNazione($datiCliente["nazione"]);?></b><br />
<?php echo gtext("PROVINCIA");?>: <b><?php echo $datiCliente["nazione"] == "IT" ? $datiCliente["provincia"] : $datiCliente["dprovincia"];?></b><br />
<?php echo gtext("CITTÀ");?>: <b><?php echo $datiCliente["citta"];?></b><br />
<?php echo gtext("TELEFONO");?>: <b><?php echo $datiCliente["telefono"];?></b><br />
<?php echo gtext("EMAIL");?>: <b><?php echo $datiCliente["username"];?></b><br />
<?php echo gtext("PEC");?>: <b><?php echo $datiCliente["pec"];?></b><br />
<?php echo gtext("CODICE DESTINATARIO");?>: <b><?php echo $datiCliente["codice_destinatario"];?></b><br />
<br /><br />

