<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-section">
	<div class="uk-container uk-container-large">
		<div class="uk-grid" uk-grid>
			<div class="uk-width-1-2@m" id="description">
				<header>
					<nav style="overflow-x:auto;" class="nav-scroll">
						<ul class="uk-flex uk-flex-left uk-flex-nowrap uk-subnav uk-subnav-pill" uk-switcher="connect: .js-tabs">
							<?php foreach ($contenuti_tab as $tab => $aa) { ?>
							<li aria-expanded="true" class="uk-active"><a href=""><?php echo gtext($tab);?></a></li>
							<?php } ?>
							<?php if (count($caratteristiche) > 0) { ?>
							<li aria-expanded="false" class=""><a href=""><?php echo gtext("Caratteristiche");?></a></li>
							<?php } ?>
							<?php if (v("abilita_feedback")) { ?>
							<li aria-expanded="false" class=""><a href=""><?php echo gtext("Valutazioni clienti");?></a></li>
							<?php } ?>
						</ul>
					</nav>
				</header>
				<div class="uk-card-body uk-padding-remove uk-margin-medium-top">
					<div class="uk-switcher js-product-switcher js-tabs">
						<?php foreach ($contenuti_tab as $tab => $valoriContenuto) { ?>
						<section class="uk-active uk-text-left">
							<?php foreach ($valoriContenuto as $cont) { ?>
							<h2 class="uk-text-lead uk-text-uppercase"><?php echo contfield($cont, "titolo");?></h2>
							<div class="uk-margin">
								<?php echo htmlentitydecode(contfield($cont, "descrizione"));?>
							</div>
							<?php } ?>
						</section>
						<?php } ?>
						<?php if (count($caratteristiche) > 0) { ?>
						<section class="uk-text-left">
							<h2 class="uk-text-lead uk-text-uppercase uk-margin-medium-bottom"><?php echo gtext("Caratteristiche");?></h2>
							<table class="uk-text-left uk-table uk-table-divider uk-table-justify uk-table-responsive">
								<tbody>
									<?php foreach ($caratteristiche as $car) { ?>
									<tr>
										<th class="uk-width-medium"><?php echo carfield($car, "titolo");?></th>
										<td class="uk-table-expand"><?php echo carvfield($car, "titolo");?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
						</section>
						<?php } ?>
						<?php if (v("abilita_feedback")) { ?>
						<section class="uk-text-left">
							<h2 class="uk-text-lead uk-text-uppercase uk-margin-medium-bottom"><?php echo gtext("Valutazioni clienti");?></h2>
							
							<div>
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
							
						</section>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="uk-width-1-2@m" id="contatti-form">
				<div class="uk-background-muted uk-padding uk-text-left">
<!-- 					<div class="uk-padding-large uk-card uk-card-body uk-background-muted">    -->
					    	<h3 class="uk-text-uppercase uk-margin-remove uk-text-default">Contattaci</h3>  	
							<h2 class="uk-margin-remove uk-margin uk-text-emphasis uk-text-large">Hai bisogno di maggiori info? </h2>

							<hr>
							<p>Se hai dubbi o domande compila il seguente form. Ti risponderemo al più presto!</p>
					<?php include(tpf("/Elementi/Pagine/form-contatti.php"));?>
				</div>
			</div>
		</div>
	</div>
</div>
