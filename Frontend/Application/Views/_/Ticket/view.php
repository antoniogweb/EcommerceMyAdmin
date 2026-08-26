<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (User::$logged)
{
	$breadcrumb = array(
		gtextPlain("Home") 		=> $this->baseUrl,
		gtextPlain("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtextPlain("Ticket assistenza") => $this->baseUrl."/ticket/",
		gtextPlain("Dettaglio ticket") => "",
	);
}
else
{
	$breadcrumb = array(
		gtextPlain("Home") 		=> $this->baseUrl,
		gtextPlain("Dettaglio ticket") => "",
	);
}

$titoloPagina = gtextPlain("Ticket assistenza");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ticket";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<script>
	var idTicket = <?php echo $idTicket;?>;
	var ticketUid = "<?php echo $ticket["ticket_uid"];?>";
</script>

<div class="view_partial">
	<?php include(tpf("Ticket/view_partial.php")); ?>
</div>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
