<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-section uk-section-muted">
	<div class="uk-container">
		<div class="uk-width-1-1 uk-grid-margin uk-first-column" id="description">
			<header>
				<nav style="overflow-x:auto;" class="nav-scroll">
					<ul class="uk-flex uk-flex-center uk-flex-nowrap uk-subnav uk-subnav-pill" uk-switcher="connect: .js-tabs">
						<?php foreach ($contenuti_tab as $tab => $aa) { ?>
						<li aria-expanded="true" class="uk-active"><a href=""><?php echo gtext($tab);?></a></li>
						<?php } ?>
						<?php if (count($caratteristiche) > 0) { ?>
						<li aria-expanded="false" class=""><a href=""><?php echo gtext("Caratteristiche");?></a></li>
						<?php } ?>
					</ul>
				</nav>
			</header>
			<div class="uk-card-body">
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
					<section>
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
				</div>
			</div>
		</div>
	</div>
</div>
