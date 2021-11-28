<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<main class="<?php echo v("page_main_class");?>">
	<section class="uk-section uk-section-small">
		<?php if (!isset($noContainer)) { ?>
		<div class="uk-container">
		<?php } ?>
			<div class="uk-text-center">
				<ul class="uk-breadcrumb">
					<?php include(tpf("/Elementi/breadcrumb.php"));?>
				</ul>
				<?php if (!isset($noTitolo)) { ?>
				<h1 class="uk-margin-small-top uk-margin-remove-bottom">
					<?php echo isset($titoloPagina) ? $titoloPagina : cfield($datiCategoria, "title");?>
					<?php if (isset($titoloAggiuntivo)) echo $titoloAggiuntivo;?>
				</h1>
				<?php } ?>
				<?php if (isset($pages) && !isset($noNumeroProdotti)) { ?>
				<div class="uk-text-meta uk-margin-xsmall-top">
					<?php $numProdotti = count($pages);?>
					<div><?php echo $numProdotti." ".singPlu($numProdotti, gtext("prodotto"), gtext("prodotti"))?></div>
					
					<?php if (User::$isMobile && !isset($noFiltri)) { ?>
						<a href="#filtri-categoria" class="uk-button uk-button-default uk-margin-small-right uk-margin-top" uk-toggle="target: #filtri-categoria"><span class="uk-margin-xsmall-right" uk-icon="icon: settings; ratio: .75;"></span> <?php echo gtext("filtri");?></a>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</section>
	
	<?php if (!isset($standardPage)) { ?>
	<section class="uk-section uk-section-small">
		<div class="uk-container">
			<div class="uk-text-center">
	<?php } ?>
