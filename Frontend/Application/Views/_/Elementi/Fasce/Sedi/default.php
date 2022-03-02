<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$sedi = SediModel::getElementiFascia();

if (isset($sedi) && count($sedi) > 0) { ?>
<div uk-filter=".js-filter">
	<div class="uk-section">
		<div class="uk-container">
			<div class=" uk-margin-large-bottom">
				<h2 class="uk-text-center uk-text-bold uk-margin-remove-top uk-margin-remove-bottom"><span><?php echo t("Le nostre sedi"); ?></span></h2>
			</div>
			<div class="uk-flex uk-flex-middle">
				<div class="uk-text-small uk-margin-right uk-text-meta"><?php echo gtext("FIltra per regione");?>: </div>
				<ul class="uk-subnav uk-subnav-pill">
					<?php
					$arraySediFiltri = array();
					foreach ($sedi as $p) {
						if (!in_array($p["pages"]["id_regione"], $arraySediFiltri))
						{
							$arraySediFiltri[] = $p["pages"]["id_regione"];
						?>
						<li uk-filter-control=".regione_<?php echo $p["pages"]["id_regione"];?>"><a href="#"><?php echo RegioniModel::g(false)->where(array("id_regione"=>$p["pages"]["id_regione"]))->field("titolo");?></a></li>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
			<div class="js-filter uk-card-small uk-grid-column uk-child-width-1-3@s uk-text-center" uk-grid>
				<?php foreach ($sedi as $p) {
					include(tpf("/Elementi/Categorie/sede.php"));
				} ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>
 
