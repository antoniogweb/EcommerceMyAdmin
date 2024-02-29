<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (User::$logged)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
		gtext("Ticket assistenza") => $this->baseUrl."/ticket/",
		gtext("Dettaglio ticket") => "",
	);
}
else
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Dettaglio ticket") => "",
	);
}

$titoloPagina = gtext("Ticket assistenza");

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
