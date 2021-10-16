<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p>Un cliente si è registrato nel vostro sito web. Ecco i suoi dati:</p>

USERNAME: <b><?php echo $clean["username"];?></b><br />
<?php if (false) { ?>
PASSWORD: <b><?php echo $password;?></b><br />
<?php } ?>
<?php if (strcmp($datiCliente["tipo_cliente"],"privato") === 0 || strcmp($datiCliente["tipo_cliente"],"libero_professionista") === 0) { ?>
NOME: <b><?php echo $datiCliente["nome"];?></b><br />
COGNOME: <b><?php echo $datiCliente["cognome"];?></b><br />
<?php } ?>
<?php if (strcmp($datiCliente["tipo_cliente"],"azienda") === 0) { ?>
RAGIONE SOCIALE: <b><?php echo $datiCliente["ragione_sociale"];?></b><br />
<?php } ?>
<?php if (strcmp($datiCliente["tipo_cliente"],"azienda") === 0 || strcmp($datiCliente["tipo_cliente"],"libero_professionista") === 0) { ?>
PARTITA IVA: <b><?php echo $datiCliente["p_iva"];?></b><br />
<?php } ?>
CODICE FISCALE: <b><?php echo $datiCliente["codice_fiscale"];?></b><br />
INDIRIZZO: <b><?php echo $datiCliente["indirizzo"];?></b><br />
CAP: <b><?php echo $datiCliente["cap"];?></b><br />
NAZIONE: <b><?php echo nomeNazione($datiCliente["nazione"]);?></b><br />
PROVINCIA: <b><?php echo $datiCliente["nazione"] == "IT" ? $datiCliente["provincia"] : $datiCliente["dprovincia"];?></b><br />
CITTÀ: <b><?php echo $datiCliente["citta"];?></b><br />
TELEFONO: <b><?php echo $datiCliente["telefono"];?></b><br />
EMAIL: <b><?php echo $datiCliente["username"];?></b><br />
PEC: <b><?php echo $datiCliente["pec"];?></b><br />
CODICE DESTINATARIO: <b><?php echo $datiCliente["codice_destinatario"];?></b><br />
<br /><br />

