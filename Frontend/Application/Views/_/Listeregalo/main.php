<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	$this->baseUrl."/area-riservata",
	gtext("Liste nascita / regalo") => "",
);

$titoloPagina = gtext("Liste nascita / regalo");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "listeregalo";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<?php if (count($liste) > 0) { ?>
<div class="uk-overflow-auto">
	<table class="uk-table uk-table-divider uk-table-hover" cellspacing="0">
		<thead>
			<tr class="ordini_head">
				<th><?php echo gtext("Titolo");?></th>
				<th width="1%"></th>
				<th width="1%"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($liste as $lista) { ?>
			<tr class="">
				<td><?php echo $lista["liste_regalo"]["titolo"];?></td>
				<td><a class="td_edit" title="<?php echo gtext("Modifica",false);?>" class="link_grigio" href="<?php echo $this->baseUrl."/listeregalo/modifica/".$lista["liste_regalo"]["id_lista_regalo"];?>" uk-icon="icon: pencil"></a></td>
				<td>
					<?php if ($lista["liste_regalo"]["attivo"] == "Y") { ?>
					<a class="uk-text-bold td_edit uk-text-danger" title="<?php echo gtext("La lista non è attiva. Disattiva la lista",false);?>" href="<?php echo $this->baseUrl."/liste-regalo/?valore=N&id_lista=".$lista["liste_regalo"]["id_lista_regalo"];?>" uk-icon="icon: close"></a>
					<?php } else { ?>
					<a class="uk-text-bold td_edit" title="<?php echo gtext("Attiva la lista",false);?>" href="<?php echo $this->baseUrl."/liste-regalo/?valore=Y&id_lista=".$lista["liste_regalo"]["id_lista_regalo"];?>" uk-icon="icon: ban"></a>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php } else { ?>
<p><?php echo gtext("Non hai alcun indirizzo configurato");?></p>
<?php } ?>

<div class="uk-margin">
	<a class="uk-button uk-button-secondary" href="<?php echo $this->baseUrl."/listeregalo/modifica/0";?>"><span uk-icon="plus"></span> <?php echo gtext("Crea lista nascita / regalo");?></a>
</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
