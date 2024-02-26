<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Ticket assistenza") => "",
);

$titoloPagina = gtext("Ticket assistenza");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "ticket";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($ticket) > 0) { ?>

<?php } else { ?>
<p><?php echo gtext("Non hai creato alcun ticket.");?></p>
<?php } ?>

<div class="uk-margin">
	<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/ticket/form/insert/0";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/plus.svg");?></span></span> <?php echo gtext("Crea un ticket di assistenza");?></a>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
