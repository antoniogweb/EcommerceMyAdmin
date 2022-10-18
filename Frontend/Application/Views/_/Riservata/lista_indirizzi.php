<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Indirizzi di spedizione") => "",
);

$titoloPagina = gtext("Indirizzi di spedizione");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "indirizzi";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($indirizzi) > 0) { ?>
<div class="uk-overflow-auto">
	<table class="uk-table uk-table-divider uk-table-hover" cellspacing="0">
		<thead>
			<tr class="ordini_head">
				<th><?php echo gtext("Indirizzo");?></th>
				<th><?php echo gtext("Cap");?></th>
				<th><?php echo gtext("Nazione");?></th>
				<th><?php echo gtext("Città");?></th>
				<th><?php echo gtext("Provincia");?></th>
				<th><?php echo gtext("Telefono");?></th>
				<th width="1%"></th>
				<th width="1%"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($indirizzi as $indirizzo) { ?>
			<tr class="">
				<td><?php echo $indirizzo["spedizioni"]["indirizzo_spedizione"];?></td>
				<td><?php echo $indirizzo["spedizioni"]["cap_spedizione"];?></td>
				<td><?php echo nomeNazione($indirizzo["spedizioni"]["nazione_spedizione"]);?></td>
				<td><?php echo $indirizzo["spedizioni"]["citta_spedizione"];?></td>
				<td><?php echo $indirizzo["spedizioni"]["nazione_spedizione"] == "IT" ? $indirizzo["spedizioni"]["provincia_spedizione"] : $indirizzo["spedizioni"]["dprovincia_spedizione"];?></td>
				<td><?php echo $indirizzo["spedizioni"]["telefono_spedizione"];?></td>
				<td><a class="td_edit" title="<?php echo gtext("Modifica",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/gestisci-spedizione/".$indirizzo["spedizioni"]["id_spedizione"];?>" uk-icon="icon: pencil"></a></td>
				<td><a class="uk-text-bold td_edit uk-text-danger" title="<?php echo gtext("Elimina",false);?>" href="<?php echo $this->baseUrl."/riservata/indirizzi?del=".$indirizzo["spedizioni"]["id_spedizione"];?>" uk-icon="icon: trash"></a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php } else { ?>
<p><?php echo gtext("Non hai alcun indirizzo configurato");?></p>
<?php } ?>

<div class="uk-margin">
	<a class="uk-button uk-button-secondary" href="<?php echo $this->baseUrl."/gestisci-spedizione/0";?>"><span uk-icon="plus"></span> <?php echo gtext("Aggiungi indirizzo");?></a>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
