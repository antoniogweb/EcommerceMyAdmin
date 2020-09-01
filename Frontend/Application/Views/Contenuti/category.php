<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p class="breadcrumb"><span class="testo_sei_qui">sei qui:</span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo $breadcrumb;?></p>

<div id="main">

	<h1><?php echo $datiCategoria["title"];?></h1>
	
	<?php if (count($iChildren) > 0) { ?>
	<h3>Lista sotto-categorie</h3>
	<ul>
	<?php foreach ($iChildren as $c) { ?>
		<li><a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($c["categories"]["id_c"]);?>"><?php echo $c["categories"]["title"];?></a></li>
	<?php } ?>
	</ul>
	<?php } ?>
	
	<div class="elenco_contenuti">
		<?php foreach ($pages as $p) {
		$idPr = getPrincipale(field($p, "id_page"));
		?>
		<div class="item">
			<?php $urlAlias = getUrlAlias($p["pages"]["id_page"]); ?>
			<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><img src="<?php echo $this->baseUrlSrc."/thumb/contenuto/".getFirstImage($idPr);?>" /></a>
			<a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo field($p, "title");?></a>
			
		</div>
		<?php } ?>
	</div>
	
	<?php if ($rowNumber > $elementsPerPage) { ?>
	<?php echo $pageList;?>
	<?php } ?>
	
</div>
