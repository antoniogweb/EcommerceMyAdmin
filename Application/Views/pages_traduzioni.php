<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php $nazioneUrl = v("attiva_nazione_nell_url") ? "_".strtolower(v("nazione_default")) : "";?>
<?php
$pmT = new PagesModel();
$cmT = new CategoriesModel();
?>

<table class="table table-striped">
	<tr>
		<th><?php echo gtext("Titolo");?></th>
		<?php if (!isset($nascondiAlias)) { ?>
		<th><?php echo gtext("Alias");?></th>
		<?php } ?>
		<th><?php echo gtext("Lingua");?></th>
		<th></th>
		<?php if (!isset($nascondiLink)) { ?>
		<th></th>
		<?php } ?>
	</tr>
	<?php foreach ($contenutiTradotti as $trad) { ?>
	<tr>
		<td><?php echo $trad["title"] ? $trad["title"] : $trad["titolo"]; ?></td>
		<?php if (!isset($nascondiAlias)) { ?>
		<td style="max-width:180px;"><div style="word-wrap:break-word;"><?php echo $trad["alias"]; ?></div></td>
		<?php } ?>
		<td><?php echo strtoupper($trad["lingua"]); ?></td>
		<td><a class="iframe" title="<?php if ($trad["salvato"]) { ?>Modifica<?php } else { ?>Inserisci<?php } ?> traduzione" href="<?php echo $this->baseUrl."/contenutitradotti/form/update/".$trad["id_ct"]."?partial=Y&section=$section";?>">
			<?php if ($trad["salvato"]) { ?>
			<i class="fa fa-edit"></i>
			<?php } else { ?>
			<i class="fa fa-plus"></i>
			<?php } ?>
		</a></td>
		<?php if (!isset($nascondiLink)) { ?>
		<td>
			<?php
			if ($trad["id_page"])
				$urlT = $pmT->getUrlAlias($trad["id_page"], $trad["lingua"]);
			else
				$urlT = $cmT->getUrlAlias($trad["id_c"], $trad["lingua"]);
			?>
			<?php include($this->viewPath("pages_traduzioni_link"));?>
		</td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
