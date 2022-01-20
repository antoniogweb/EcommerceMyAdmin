<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<h2 class="uk-text-lead uk-text-uppercase uk-margin-medium-bottom"><?php echo gtext("Valutazioni clienti");?></h2>
<div class="box_feedback">
	<?php foreach ($page_feedback as $pf) { ?>
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
			</div>
		</section>
		<hr />
	</article>
	<?php } ?>
</div>
