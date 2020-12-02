<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
  <div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » cerca</p>
				</div>
				<div class="page-header mb-2 w-100 order-first">
					<h2 class="page-title">Risultati della ricerca "<?php echo $s;?>"</h2>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="site-content-contain">
	<div id="content" class="site-content">
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<header class="woocommerce-products-header"></header>
					<div class="osf-active-filters">
						<span class="osf_active_filters_label">Active Filters: </span>
						<a class="clear-all" href="/index.php/product-category/all/">Clear Filters</a>
					</div>
					<div class="woocommerce-notices-wrapper"></div>
					<div class="columns-4">
						<ul class="products columns-4">
							<?php if (count($pages) > 0) { ?>
								<?php include(ROOT."/Application/Views/Contenuti/items.php");?>
							<?php } ?>
						</ul>
					</div>
				</main>
				<!-- #main -->
			</div>
			
		</div>
	</div>
	<!-- #content -->
</div>
