<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	"",
);

$titoloPagina = gtext("Area riservata");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "dashboard";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
	<p><?php echo gtext("Ciao")?> <strong><?php echo $nomeCliente;?></strong> (<?php echo gtext("non sei")?> <strong><?php echo $nomeCliente;?></strong>? <a href="<?php echo $this->baseUrl."/esci";?>"><?php echo gtext("Esci")?></a>)</p>

	<p><?php echo gtext("Dalla tua area riservata puoi vedere i")?> <a href="<?php echo $this->baseUrl."/ordini-effettuati"?>"><?php echo gtext("tuoi ordini effettuati")?></a> <?php echo gtext("e gestire i tuoi")?>  <a href="<?php echo $this->baseUrl."/modifica-account"?>"><?php echo gtext("dati di fatturazione");?></a>.</p>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
