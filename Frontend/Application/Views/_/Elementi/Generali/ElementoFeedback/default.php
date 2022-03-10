<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<hr class="uk-divider-small">
<div id="feedback_<?php echo $pf["feedback"]["id_feedback"];?>">
	<div uk-grid="" class="uk-flex uk-flex-middle uk-margin-small uk-grid uk-child-width-1-2@s uk-child-width-1-1">
		<div class="uk-first-column">
			<?php
			$punteggio = $pf["feedback"]["voto"];
			include(tpf("/Elementi/feedback_stars.php"));
			?>
		</div>

		<div class="uk-text-muted uk-text-right@s uk-text-left uk-text-small"><?php echo date("d", strtotime($pf["feedback"]["data_feedback"]));?> <?php echo gtext(traduci(date("F", strtotime($pf["feedback"]["data_feedback"]))));?> <?php echo date("Y", strtotime($pf["feedback"]["data_feedback"]));?></div>

	</div>

	<?php echo htmlentitydecode($pf["feedback"]["testo"]);?>
	
	<div class="uk-text-muted uk-text-small uk-margin"><?php echo $pf["feedback"]["autore"];?></div>
	
	<?php if (!F::blank($pf["feedback"]["commento_negozio"])) { ?>
	<div class="uk-margin uk-margin-left uk-background-muted uk-padding-small uk-texm-small">
		<div class="uk-text-emphasis uk-text-small uk-margin-bottom"><span uk-icon="comments"></span> <?php echo gtext("Commento del negozio:");?></div>
		<div class="uk-text-meta uk-text-small uk-text-italic"><?php echo htmlentitydecode($pf["feedback"]["commento_negozio"]);?></div>
	</div>
	<?php } ?>
</div>
