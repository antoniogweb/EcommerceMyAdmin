<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$standardPage = false;
$filtriOffCanvas = true;

if ($isPromo)
{
	$breadcrumb = array(
		gtext("Home") 		=> $this->baseUrl,
		gtext("Promozioni")	=>	"",
	);
	
	$descrizioneNoProdotti = gtext("Non è presente alcun articolo");
	$titoloPagina = gtext("Prodotti in promozione");
}

$titoloAggiuntivo = "";

if (isset($tagCorrente) && !empty($tagCorrente))
{
	if (isset($id_categoria) && $id_categoria == CategoriesModel::$idShop)
		$titoloPagina = tagfield($tagCorrente,"titolo");
	else
		$titoloAggiuntivo .= " - " . tagfield($tagCorrente,"titolo");
}

if (isset($marchioCorrente) && !empty($marchioCorrente))
	$titoloAggiuntivo .= " - ".mfield($marchioCorrente,"titolo");

$sottotitoloPagina = (isset($datiCategoria) && trim(cfield($datiCategoria, "sottotitolo"))) ? cfield($datiCategoria, "sottotitolo") : "";

include(tpf("/Elementi/Pagine/page_top.php"));

if (!isset($noFiltri))
	include(tpf("/Elementi/Categorie/filtri_variabili.php"));
?>

<section class="uk-section uk-section-small">
	<div class="uk-container">
		<div class="uk-text-center">
			<div class="" uk-grid>
				<?php if (!isset($noFiltri)) { ?>
					<?php if (User::$isMobile) { ?>
					<div id="filtri-categoria" uk-offcanvas="overlay: true" class="uk-text-small">
						<div class="uk-offcanvas-bar">
							<div class="uk-flex uk-flex-right">
								<button uk-toggle="target: #filtri-categoria" type="button" uk-close></button>
							</div>
					<?php } ?>
							<?php
							include(tpf("/Elementi/Categorie/filtri_categoria.php"));
							?>
					<?php if (User::$isMobile) { ?>
						</div>
					</div>
					<?php } ?>
				<?php } ?>
				
				<?php if (!isset($itemFile))
					$itemFile = "/Elementi/Categorie/prodotto.php";
				?>
				<div class="uk-width-expand">
					<?php if (!isset($noFiltri) && !isset($noOrdinamento)) { ?>
					<div class="uk-margin-bottom" uk-grid>
						<div class="uk-width-1-1 uk-width-3-5@s uk-text-left">
							<?php include(tpf("Elementi/Categorie/filtri_attivi.php")); ?>
						</div>
						<?php if (!isset($noOrdinamento)) { ?>
						<div class="uk-width-1-1 uk-width-2-5@s uk-text-right">
							<?php include(tpf("Elementi/Categorie/scelta_ordinamento.php")); ?>
						</div>
						<?php } ?>
					</div>
					<?php } ?>
					<?php if (count($pages) > 0) { ?>
						<div class="uk-card-small uk-grid-column uk-child-width-1-3@s uk-text-center" uk-grid>
							<?php foreach ($pages as $p) {
								include(tpf($itemFile));
							} ?>
						</div>
						<?php if (isset($pageList) && isset($rowNumber) && isset($elementsPerPage)) { ?>
							<?php if ($rowNumber > $elementsPerPage) { ?>
							<ul class="uk-pagination uk-flex-right uk-margin-medium-top">
								<?php echo $pageList;?>
							</ul>
							<?php } ?>
						<?php } ?>
					<?php } else { ?>
						<article class="uk-article">
							<p class="uk-article-meta">
								<?php echo isset($descrizioneNoProdotti) ? $descrizioneNoProdotti : gtext("Nessun elemento trovato");?>
							</p>
						</article>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
