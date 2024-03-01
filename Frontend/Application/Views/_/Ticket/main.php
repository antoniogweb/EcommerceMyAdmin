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
	<div class="uk-visible@m">
		<div class="uk-text-meta uk-text-uppercase uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<?php echo gtext("Data crezione");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Oggetto");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Tipologia");?>
			</div>
			<div class="uk-first-column">
				<?php echo gtext("Stato");?>
			</div>
			<div class="uk-first-column"></div>
		</div>
	</div>
	<hr>
	<?php foreach ($ticket as $t) { ?>
	<div>
		<div class="uk-text-small uk-flex uk-flex-middle uk-grid-small uk-child-width-1-1 uk-child-width-expand@s uk-text-left uk-text-center@m uk-grid" uk-grid="">
			<div class="uk-first-column uk-text-left">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Data crezione");?>:</span> <?php echo date("d-m-Y H:i", $t["ticket"]["stato"] == "B" ? strtotime($t["ticket"]["data_creazione"]) : strtotime($t["ticket"]["data_invio"]));?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Oggetto");?>:</span> <?php echo $t["ticket"]["oggetto"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Tipologia");?>:</span> <?php echo $t["ticket_tipologie"]["titolo"];?>
			</div>
			<div class="uk-first-column">
				<span class="uk-hidden@m uk-text-bold"><?php echo gtext("Stato");?>:</span> <span class="uk-label" style="<?php echo TicketModel::getStile($t["ticket"]["stato"]);?>"><?php echo TicketModel::getTitoloStato($t["ticket"]["stato"]);?></span>
			</div>
			<div class="uk-first-column uk-text-left uk-text-right@m">
				<a class="td_edit" title="<?php echo gtext("Modifica",false);?>" class="" href="<?php echo $this->baseUrl."/ticket/view/".$t["ticket"]["id_ticket"]."/".$t["ticket"]["ticket_uid"];?>">
					<span class="uk-icon uk-text-secondary"><?php include tpf("Elementi/Icone/Svg/pencil.svg");?></span>
				</a>
				<?php if ($t["ticket"]["stato"] == "B") { ?>
				<a class="uk-margin-left uk-text-bold td_edit uk-text-danger del_ticket" title="<?php echo gtext("Elimina",false);?>" href="<?php echo $this->baseUrl."/ticket/?del=".$t["ticket"]["id_ticket"];?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/trash.svg");?></span></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<hr>
	<?php } ?>
<?php } else { ?>
<p><?php echo gtext("Non hai creato alcuna richiesta di assistenza.");?></p>
<?php } ?>
<?php if (count($tipologie) > 0) { ?>
<div class="uk-margin">
	<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/ticket/add/";?>"><span class="uk-icon"><?php include tpf("Elementi/Icone/Svg/plus.svg");?></span></span> <?php echo gtext("Crea un ticket di assistenza");?></a>
</div>
<?php } ?>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
