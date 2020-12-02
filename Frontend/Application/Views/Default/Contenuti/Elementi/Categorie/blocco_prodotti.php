<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="osf-sorting">
	<div class="osf-sorting-group col-lg-6 col-sm-12">
		<p class="woocommerce-result-count"><?php echo count($pages);?> <?php echo count($pages) == 1 ? "prodotto" : "prodotti";?></p>
	</div>
	<div class="osf-sorting-group col-lg-6 col-sm-12">
		<form class="woocommerce-ordering" method="get" action="<?php echo isset($url_ordinamento) ? $url_ordinamento : "";?>">
			<?php
			$arrayordinamenti = array(
				"tutti"			=>	gtext("Ordinamento predefinito", true),
				"crescente"		=>	gtext("Prezzo: dal più economico", true),
				"decrescente"	=>	gtext("Prezzo: dal più caro", true),
			);
			?>
			<?php echo Html_Form::select("o",$this->viewArgs["o"],$arrayordinamenti, "tendina_ordinamento orderby", null, "yes");?>
			<input type="hidden" name="paged" value="1" />
			<div class="select-selected">
				<?php
					echo isset($arrayordinamenti[$this->viewArgs["o"]]) ? $arrayordinamenti[$this->viewArgs["o"]] : "Ordinamento predefinito";
				?>
			</div>
			<div class="select-items select-hide">
				<?php foreach ($arrayordinamenti as $tipo => $nomeOrd) { ?>
				<div class="select_ordinamento" o="<?php echo $tipo;?>"><?php echo $nomeOrd;?></div>
				<?php } ?>
			</div>
		</form>
	</div>
</div>
<div class="osf-active-filters">
	<span class="osf_active_filters_label">Active Filters: </span>
	<a class="clear-all" href="/index.php/product-category/all/">Clear Filters</a>
</div>
<div class="woocommerce-notices-wrapper"></div>
<div class="columns-4">
	<ul class="products columns-4">
		<?php if (count($pages) > 0) { ?>
			<?php include(tp()."/Contenuti/Elementi/Categorie/prodotti.php");?>
		<?php } ?>
	</ul>
</div>
