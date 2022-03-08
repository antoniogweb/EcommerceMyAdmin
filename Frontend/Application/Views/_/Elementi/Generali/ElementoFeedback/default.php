<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<article class="uk-first-column">
	<section class="uk-grid-small uk-child-width-1-1 uk-grid uk-grid-stack" uk-grid="">
		<header class="uk-first-column">
			<div class="uk-h4 uk-margin-remove"><?php echo $pf["feedback"]["autore"];?></div>
			<time class="uk-text-meta"><?php echo date("d", strtotime($pf["feedback"]["data_feedback"]));?> <?php echo gtext(traduci(date("F", strtotime($pf["feedback"]["data_feedback"]))));?> <?php echo date("Y", strtotime($pf["feedback"]["data_feedback"]));?></time>
		</header>
		<div class="uk-grid-margin uk-first-column">
			<?php
			$punteggio = $pf["feedback"]["voto"];
			include(tpf("/Elementi/feedback_stars.php"));
			?>
			<div class="uk-margin">
				<?php echo htmlentitydecode($pf["feedback"]["testo"]);?>
			</div>
			
			<?php if (!F::blank($pf["feedback"]["commento_negozio"])) { ?>
			<div class="uk-margin uk-margin-left uk-background-muted uk-padding-small uk-texm-small">
				<div class="uk-text-emphasis uk-text-small"><?php echo gtext("Commento del negozio:");?></div>
				<div class="uk-text-meta uk-text-small uk-text-italic"><?php echo htmlentitydecode($pf["feedback"]["commento_negozio"]);?></div>
			</div>
			<?php } ?>
		</div>
	</section>
	<hr />
</article> 
