<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
$urlAlias = getUrlAlias($p["pages"]["id_page"]);
$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);

$titoloPagina = field($p, "title");
$noNumeroProdotti = $noTitolo = $noContainer = $noContainer = true;

include(tpf("/Elementi/Pagine/page_top.php"));?>

<section class="uk-section uk-article uk-text-left uk-padding-remove-top">
	<div class="uk-container uk-container-small">
		<h2 class="uk-text-bold uk-h1 uk-margin-remove-adjacent uk-margin-remove-top"><?php echo field($p, "title");?></h2>
		<p class="uk-article-meta"><?php echo gtext("Scritto in data");?> <?php echo traduci(date("d M Y", strtotime($p["pages"]["data_news"])));?>. <?php echo gtext("Categoria");?> <a href="<?php echo $this->baseUrl."/$urlAliasCategoria";?>"><?php echo cfield($p, "title");?></a></p>
		
		<p class="uk-text-lead"><?php echo htmlentitydecode(field($p, "sottotitolo"));?></p>
	</div>
	<?php if (count($altreImmagini) > 0) { ?>
	<div class="uk-container uk-section">
		<div class="uk-position-relative uk-visible-toggle uk-light" data-uk-slideshow="ratio: 7:3; animation: push; min-height: 270; velocity: 3">
			<ul class="uk-slideshow-items" style="min-height: 520px">
				<?php foreach ($altreImmagini as $imm) { ?>
				<li>
					<img src="<?php echo $this->baseUrlSrc."/thumb/blogdetail/".$imm["immagine"];?>" alt="<?php echo urlencode(field($p, "title"));?>" data-uk-img="target: !.uk-slideshow-items" data-uk-cover></a>
				</li>
				<?php } ?>
			</ul>
			<a class="uk-position-center-left uk-position-small uk-hidden-hover" href="#" data-uk-slidenav-previous="ratio: 1.5" data-uk-slideshow-item="previous"></a>
			<a class="uk-position-center-right uk-position-small uk-hidden-hover" href="#" data-uk-slidenav-next="ratio: 1.5" data-uk-slideshow-item="next"></a>
		</div>
	</div>
	<?php } ?>
	
	<div class="uk-container uk-container-small">
		<div class="uk-margin"><?php echo htmlentitydecode(attivaModuli(field($p, "description")));?></div>
	</div>
</div>

<?php include(tpf("/Fasce/ultimi_articoli.php"));?>

<?php include(tpf("/Elementi/Pagine/page_bottom.php"));?>
<?php } ?>
