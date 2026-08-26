<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtextPlain("Home") 		=> $this->baseUrl,
	gtextPlain("Area riservata")	=>	"",
);

$titoloPagina = gtextPlain("Area riservata");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "dashboard";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
	<p><?php echo gtextPlain("Ciao")?> <strong><?php echo $nomeCliente;?></strong> (<?php echo gtextPlain("non sei")?> <strong><?php echo $nomeCliente;?></strong>? <a href="<?php echo $this->baseUrl."/esci";?>"><?php echo gtextPlain("Esci")?></a>)</p>

	<p><?php echo gtextPlain("Dalla tua area riservata puoi vedere i")?> <a href="<?php echo $this->baseUrl."/ordini-effettuati"?>"><?php echo gtextPlain("tuoi ordini effettuati")?></a> <?php echo gtextPlain("e gestire i tuoi")?>  <a href="<?php echo $this->baseUrl."/".Url::routeToUrl("modifica-account");?>"><?php echo gtextPlain("dati di fatturazione");?></a>.</p>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
