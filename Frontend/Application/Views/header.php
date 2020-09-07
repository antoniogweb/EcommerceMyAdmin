<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="it-IT" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title;?></title>
	<meta name="description" content="<?php echo $meta_description;?>" />
	<meta name="keywords" content="<?php echo $keywords;?>" />
	
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/favicon-16x16.png">
	<link rel="manifest" href="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo $this->baseUrlSrc."/Public/Img/favicon/"?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<style type="text/css">
		img.wp-smiley,
		img.emoji {
			display: inline !important;
			border: none !important;
			box-shadow: none !important;
			height: 1em !important;
			width: 1em !important;
			margin: 0 .07em !important;
			vertical-align: -0.1em !important;
			background: none !important;
			padding: 0 !important;
		}
	</style>
	<link rel='stylesheet' id='opal-boostrap-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/css/opal-boostrap.css' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-frontend-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/css/frontend.min.css' type='text/css' media='all' />
	<link rel='stylesheet' id='auros-style-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/style.css' type='text/css' media='all' />
	<link rel='stylesheet' id='auros-style-css'  href='<?php echo $this->baseUrlSrc."/Public/Css/"?>style.css' type='text/css' media='all' />
	<?php if ($inlineCssFile) { ?>
	<link rel='stylesheet' id='auros-style-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/$inlineCssFile"?>' type='text/css' media='all' />
	<?php } else { ?>
	<link rel='stylesheet' id='auros-style-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>auros-css-inline-home.css' type='text/css' media='all' />
	<?php } ?>
	<link rel='stylesheet' id='osf-elementor-addons-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/css/elementor/style.css' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-post-341-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-341.css' type='text/css' media='all' />
	<link rel='stylesheet' id='magnific-popup-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/css/magnific-popup.css' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-post-362-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-362.css' type='text/css' media='all' />
	<link rel='stylesheet' id='wp-block-library-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>wp-includes/css/dist/block-library/style.min.css' type='text/css' media='all' />
<!-- 	<link rel='stylesheet' id='wc-block-style-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/packages/woocommerce-blocks/build/style.css' type='text/css' media='all' /> -->
	<link rel='stylesheet' id='contact-form-7-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/contact-form-7/includes/css/styles.css' type='text/css' media='all' />
	<link rel='stylesheet' id='rs-plugin-settings-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/revslider/public/assets/css/settings.css' type='text/css' media='all' />

	<?php if ($isProdotto) { ?>
	<link rel='stylesheet' id='photoswipe-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/css/photoswipe/photoswipe.css?ver=3.7.0' type='text/css' media='all' />
	<link rel='stylesheet' id='photoswipe-default-skin-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/css/photoswipe/default-skin/default-skin.css?ver=3.7.0' type='text/css' media='all' />
	<?php } ?>

	<?php if (isset($isPage)) { ?>
		<?php foreach ($pages as $p) {
			$urlAlias = getUrlAlias($p["pages"]["id_page"]);
		?>
			<!-- for Facebook -->       
			<meta property="og:title" content="<?php echo tagliaStringa($p["pages"]["title"],1000);?>" />
			<meta property="og:type" content="article" />
			<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
			<meta property="og:image" content="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" />
			<?php } ?>
			<meta property="og:url" content="<?php echo $this->baseUrl."/$urlAlias";?>" />
			<meta property="og:description" content="<?php echo tagliaStringa($p["pages"]["description"],200);?>" />
			
			<!-- for Twitter -->          
			<meta name="twitter:card" content="summary" />
			<meta name="twitter:title" content="<?php echo tagliaStringa($p["pages"]["title"],1000);?>" />
			<meta name="twitter:description" content="<?php echo tagliaStringa($p["pages"]["description"],200);?>" />
			
			<?php if (strcmp($p["pages"]["immagine"],"") !== 0) { ?>
			<meta name="twitter:image" content="<?php echo $this->baseUrlSrc."/thumb/dettaglio/".$p["pages"]["immagine"];?>" />
			<?php } ?>
		<?php } ?>
	<?php } ?>

<style id='rs-plugin-settings-inline-css' type='text/css'>
#rs-demo-id {}
</style>
<style id='woocommerce-inline-inline-css' type='text/css'>
.woocommerce form .form-row .required { visibility: visible; }
</style>
<link rel='stylesheet' id='elementor-icons-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/eicons/css/elementor-icons.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-animations-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/animations/animations.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-global-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/global.css' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-204-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-204.css' type='text/css' media='all' />
<link rel='stylesheet' id='auros-opal-icon-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/css/opal-icons.css' type='text/css' media='all' />
<link rel='stylesheet' id='auros-carousel-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/css/carousel.css' type='text/css' media='all' />
<link rel='stylesheet' id='auros-woocommerce-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/css/woocommerce.css' type='text/css' media='all' />
<link rel='stylesheet' id='otf-plugin-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/css/auros-plugin.css' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-icons-shared-0-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/font-awesome/css/fontawesome.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-icons-fa-brands-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/font-awesome/css/brands.min.css' type='text/css' media='all' />

<?php if ($pagesCss) { ?>
<style rel='stylesheet' type='text/css' media='all'>
	<?php echo html_entity_decode($pagesCss);?>
</style>
<?php } ?>
<?php if (isset($isPage) && file_exists(ROOT."/Public/Tema/".$p["pages"]["id_page"]."-css.css")) { ?>
	<?php foreach ($pages as $p) { ?>
	<link rel='stylesheet' id='elementor-post-19-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/".$p["pages"]["id_page"]."-css.css"?>' type='text/css' media='all' />
	<?php } ?>
<?php } ?>
<?php if ($paginaGenerica) { ?>
<link rel='stylesheet' id='elementor-post-19-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/pagina.css"?>' type='text/css' media='all' />
<?php } ?>
<script>
	var baseUrl = "<?php echo $this->baseUrl;?>";
	var variante_non_esistente = "<?php echo gtext("Non esiste il prodotto con la combinazione di varianti selezionate", false);?>";
	var errore_combinazione = "<?php echo gtext("Si prega di selezionare la variante:", false);?>";
	var errore_quantita_minore_zero = "<?php echo gtext("Si prega di indicare una quantità maggiore di zero", false);?>";
	var errore_selezionare_variante = "<?php echo gtext("Si prega di selezionare la variante del prodotto", false);?>";
	var stringa_errore_giacenza_carrello = "<?php echo gtext("Attenzione, controllare la quantità delle righe evidenziate", false);?>";
</script>

<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>jquery/jquery.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>jquery/jquery-migrate.min.js'></script>
<script src="<?php echo $this->baseUrlSrc.'/'.FRONTEND_PATH.'/Public/Js/';?>ajaxQueue.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/'.FRONTEND_PATH.'/Public/Js/';?>functions.js?v=<?php echo rand(1,10000);?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/'.FRONTEND_PATH.'/Public/Js/';?>cart.js?v=<?php echo rand(1,10000);?>"></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/libs/modernizr.custom.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/revslider/public/assets/js/jquery.themepunch.tools.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/revslider/public/assets/js/jquery.themepunch.revolution.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/js/libs/owl.carousel.js'></script>
<!--[if lt IE 9]>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/js/libs/html5.js?ver=3.7.3'></script>
<![endif]-->
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/carousel.js'></script>

<meta name="referrer" content="always"/>
<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<style type="text/css" id="custom-background-css">
	body.custom-background { background-color: #ffffff; }
</style>
<script type="text/javascript">function setREVStartSize(e){									
	try{ e.c=jQuery(e.c);var i=jQuery(window).width(),t=9999,r=0,n=0,l=0,f=0,s=0,h=0;
		if(e.responsiveLevels&&(jQuery.each(e.responsiveLevels,function(e,f){f>i&&(t=r=f,l=e),i>f&&f>r&&(r=f,n=e)}),t>r&&(l=n)),f=e.gridheight[l]||e.gridheight[0]||e.gridheight,s=e.gridwidth[l]||e.gridwidth[0]||e.gridwidth,h=i/s,h=h>1?1:h,f=Math.round(h*f),"fullscreen"==e.sliderLayout){var u=(e.c.width(),jQuery(window).height());if(void 0!=e.fullScreenOffsetContainer){var c=e.fullScreenOffsetContainer.split(",");if (c) jQuery.each(c,function(e,i){u=jQuery(i).length>0?u-jQuery(i).outerHeight(!0):u}),e.fullScreenOffset.split("%").length>1&&void 0!=e.fullScreenOffset&&e.fullScreenOffset.length>0?u-=jQuery(window).height()*parseInt(e.fullScreenOffset,0)/100:void 0!=e.fullScreenOffset&&e.fullScreenOffset.length>0&&(u-=parseInt(e.fullScreenOffset,0))}f=u}else void 0!=e.minHeight&&f<e.minHeight&&(f=e.minHeight);e.c.closest(".rev_slider_wrapper").css({height:f})					
	}catch(d){console.log("Failure at Presize of Slider:"+d)}						
};</script>

	<?php if (ImpostazioniModel::$valori["analytics"]) { ?>
	<?php echo htmlentitydecode(ImpostazioniModel::$valori["analytics"]);?>
	<?php } ?>
</head>
<!--page-template-default page page-id-10 logged-in custom-background wp-custom-logo theme-auros woocommerce-account woocommerce-page woocommerce-no-js opal-style chrome platform-linux woocommerce-active product-style-1 opal-layout-wide opal-pagination-6 opal-page-title-top-bottom-center opal-footer-skin-light opal-comment-4 opal-comment-form-2 elementor-default-->

<?php if ($customHeaderClass) { ?>
<body class="<?php echo $customHeaderClass;?>">
<?php } else { ?>
<body class="<?php echo $headerClass;?> opal-comment-form-2<?php if ($isHome) { echo "home";} else { echo "woocommerce-page";} ?> <?php if ($islogged) { ?>logged-in woocommerce-account<?php } ?> page-template-default page page-id-204 custom-background wp-custom-logo theme-auros woocommerce-no-js opal-style chrome platform-linux woocommerce-active product-style-1 opal-layout-wide opal-pagination-6 opal-page-title-top-bottom-center opal-footer-skin-light opal-comment-4 opal-comment-form-2 auros-front-page elementor-default elementor-page elementor-page-204">
<?php } ?>
<div id="yith-wcwl-popup-message" style="display:none;"><div id="yith-wcwl-message"><?php echo gtext("Articolo aggiunto alla tua lista dei desideri.");?></div></div>
<div id="wptime-plugin-preloader"></div>
<div class="opal-wrapper">
    <div id="page" class="site">
        <header id="masthead" class="site-header">
            <div class="site-header">
    		<div data-elementor-type="wp-post" data-elementor-id="341" class="elementor elementor-341 elementor-bc-flex-widget" data-elementor-settings="[]">
			<div class="elementor-inner">
				<div class="elementor-section-wrap">
					<section class="elementor-element elementor-element-d5c6f4b elementor-section-content-middle elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="d5c6f4b" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
						<div class="elementor-container elementor-column-gap-no">
							<div class="elementor-row">
								<div class="elementor-element elementor-element-a3e191e elementor-column elementor-col-33 elementor-top-column" data-id="a3e191e" data-element_type="column">
									<div class="elementor-column-wrap  elementor-element-populated">
										<div class="elementor-widget-wrap">
											<div class="elementor-element elementor-element-46747b1 elementor-widget elementor-widget-opal-site-logo elementor-widget-image" data-id="46747b1" data-element_type="widget" data-widget_type="opal-site-logo.default">
												<div class="elementor-widget-container">
													<div class="elementor-image">
														<a href="<?php echo $this->baseUrl;?>" data-elementor-open-lightbox="">
														<img src="<?php echo $this->baseUrlSrc."/Public/Img/"?>logo–fatherson.png" title="" alt="" />                                    </a>
                                                    </div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="elementor-element elementor-element-3cbdbfe elementor-column elementor-col-33 elementor-top-column" data-id="3cbdbfe" data-element_type="column">
									<div class="elementor-column-wrap  elementor-element-populated">
										<div class="elementor-widget-wrap">
											<div class="elementor-element elementor-element-c1602d7 elementor-nav-menu--indicator-angle elementor-nav-menu--dropdown-mobile elementor-menu-toggle__align-right elementor-menu-toggle-mobile__align-right elementor-nav-menu__text-align-aside elementor-nav-menu--toggle elementor-nav-menu--burger elementor-widget elementor-widget-opal-nav-menu" data-id="c1602d7" data-element_type="widget" data-settings="{&quot;layout&quot;:&quot;horizontal&quot;,&quot;toggle&quot;:&quot;burger&quot;}" data-widget_type="opal-nav-menu.default">
												<div class="elementor-widget-container">
													<nav class="elementor-nav-menu--mobile-enable elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-horizontal e--pointer-none" data-subMenusMinWidth="270" data-subMenusMaxWidth="500">
														<ul class='elementor-nav-menu menu'>
															<?php echo $menu;?>
															<?php foreach ($arrayLingue as $l => $urlL) { ?>
															<li class="  menu-item menu-item-type-post_type menu-item-type-custom menu-item-object-custom li_menu_level li_menu_level_1 <?php echo $l;?> "><a class="elementor-item link_item  " href="<?php echo $this->baseUrlSrc."/$urlL";?>"><?php echo strtoupper($l);?></a></li>
															<?php } ?>
														</ul>
													</nav>
													<div class="elementor-menu-toggle" data-target="#menu-c1602d7">
														<i class="eicon" aria-hidden="true"></i>
														<span class="menu-toggle-title"></span>
													</div>
													<nav id="menu-c1602d7"
														class="elementor-nav-menu--canvas mp-menu">
															<ul class='nav-menu--canvas'>
																<?php echo $menu;?>
															</ul>
													</nav>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="elementor-element elementor-element-747ac74 elementor-column elementor-col-33 elementor-top-column" data-id="747ac74" data-element_type="column">
									<div class="elementor-column-wrap  elementor-element-populated">
										<div class="elementor-widget-wrap">
											<div class="elementor-element elementor-element-4406c80 elementor-search-form--skin-full_screen elementor-hidden-phone elementor-widget elementor-widget-opal-header-group" data-id="4406c80" data-element_type="widget" data-settings="{&quot;skin&quot;:&quot;full_screen&quot;}" data-widget_type="opal-header-group.default">
												<div class="elementor-widget-container">
													<div class="search-form">
														<form class="elementor-search-form" role="search" action="<?php echo $this->baseUrl."/risultati-ricerca";?>" method="get">
															<div class="elementor-search-form__toggle">
																<i class="opal-icon-search3" aria-hidden="true"></i>
															</div>
															<div class="elementor-search-form__container">
																<input placeholder="" class="elementor-search-form__input" type="search" name="s" title="Search" value="">
																<div class="dialog-lightbox-close-button dialog-close-button">
																	<i class="eicon-close" aria-hidden="true"></i>
																	<span class="elementor-screen-only">Chiudi</span>
																</div>
															</div>
														</form>
													</div>
													<div class="wish-woocommerce">
														<div class="site-header-cart menu d-flex justify-content-center">
															<a class="cart-contents header-button d-flex align-items-center" href="<?php echo $this->baseUrl."/wishlist/vedi";?>" title="">
																<i class="opal-icon-wishlist" aria-hidden="true"></i>
																<span class="title"></span>
																
																<span class="link_wishlist_num_prod <?php if ($prodInWishlist > 0) { ?>count<?php } ?> d-inline-block text-center"><?php echo $prodInWishlist ? $prodInWishlist : "";?></span>
															</a>
														</div>
													</div>
													<div class="account">
														<div class="site-header-account">
															<a class="link_account <?php if ($islogged) { ?>colore_arancione<?php } ?>" href="#"><span class="opal-icon-user3"></span></a>
															<div class="account-dropdown">
																<div class="account-wrap">
																	<div class="account-inner <?php if ($islogged) { ?>dashboard<?php } ?>">
																		<?php if ($islogged) { ?>
																		<ul class="account-dashboard">
																			<li>
																				<a href="<?php echo $this->baseUrl."/area-riservata";?>" title="<?php echo gtext("Area riservata", false);?>"><?php echo gtext("Area riservata");?></a>
																			</li>
																			<li>
																				<a href="<?php echo $this->baseUrl."/ordini-effettuati";?>" title="<?php echo gtext("Ordini effettuati", false);?>"><?php echo gtext("Ordini effettuati");?></a>
																			</li>
																			<li>
																				<a href="<?php echo $this->baseUrl."/modifica-account";?>" title="<?php echo gtext("Modifica dati fatturazione", false);?>"><?php echo gtext("Modifica dati fatturazione");?></a>
																			</li>
																			<li>
																				<a href="<?php echo $this->baseUrl."/riservata/indirizzi";?>" title="<?php echo gtext("Indirizzi di spedizione", false);?>"><?php echo gtext("Indirizzi di spedizione");?></a>
																			</li>
																			<li>
																				<a href="<?php echo $this->baseUrl."/modifica-password";?>" title="<?php echo gtext("Modifica password", false);?>"><?php echo gtext("Modifica password");?></a>
																			</li>
																			<li>
																				<a href="<?php echo $this->baseUrl."/riservata/privacy";?>" title="<?php echo gtext("Gestione della privacy", false);?>"><?php echo gtext("Gestione della privacy");?></a>
																			</li>
																			<li>
																				<a href="<?php echo $this->baseUrl."/esci";?>" title="<?php echo gtext("Esci", false);?>"><?php echo gtext("Esci");?></a>
																			</li>
																		</ul>
																		<?php } else { ?>
																		<div class="login-form-head pb-1 mb-3 bb-so-1 bc">
																			<span class="login-form-title">Entra</span>
																			<span class="pull-right">
																				<a class="register-link" href="<?php echo $this->baseUrl."/crea-account";?>"
																				title="Register"><?php echo gtext("Crea un account")?></a>
																			</span>
																		</div>
																		<form action="<?php echo $this->baseUrl."/regusers/login";?>" class="opal-login-form-ajax" data-toggle="validator" method="POST">
																			<p>
																				<label><?php echo gtext("Indirizzo e-mail")?></label>
																				<input autocomplete="new-password" name="username" type="text" required placeholder="Username">
																			</p>
																			<p>
																				<label><?php echo gtext("Password")?></label>
																				<input autocomplete="new-password" name="password" type="password" required placeholder="Password">
																			</p>
																			<button type="submit" data-button-action class="btn btn-primary btn-block w-100 mt-1">Accedi</button>
																		</form>
																		<div class="login-form-bottom">
																			<a href="<?php echo $this->baseUrl."/password-dimenticata";?>" class="mt-2 lostpass-link d-inline-block" title="<?php echo gtext("Hai dimenticato la password?", false)?>"><?php echo gtext("Hai dimenticato la password?")?></a>
																		</div>
																		<?php } ?>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="cart-woocommerce">
														<div class="site-header-cart menu d-flex justify-content-center">
															<a data-toggle="toggle" class="cart-contents header-button d-flex align-items-center" href="<?php echo $this->baseUrl."/carrello/vedi";?>" title="">
																<i class="opal-icon-cart3" aria-hidden="true"></i>
																<span class="title"></span>
																
																<span class="link_carrello_num_prod <?php if ($prodInCart > 0) { ?>count<?php } ?> d-inline-block text-center"><?php echo $prodInCart ? $prodInCart : "";?></span>
															
															</a>
															<ul class="shopping_cart carrello_secondario">
																<?php include(ROOT."/Application/Views/Cart/ajax_cart.php");?>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</header>
        
        
  
        
