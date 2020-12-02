<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="en">
   <head>
		<meta charset="UTF-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title;?></title>
		<meta name="description" content="<?php echo $meta_description;?>" />
		<meta name="keywords" content="<?php echo $keywords;?>" />
		
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
		
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,500">
		<link rel="stylesheet" href="<?php echo $this->baseUrlSrc."/".FRONTEND_PATH."/Public/Default/Css/"?>uikit.min.css" />
		
		<?php if (ImpostazioniModel::$valori["analytics"]) { ?>
		<?php echo htmlentitydecode(ImpostazioniModel::$valori["analytics"]);?>
		<?php } ?>
   </head>
   <body>
      <div class="uk-offcanvas-content">
         <header>
            <div class="uk-navbar-container tm-toolbar-container">
               <div class="uk-container" uk-navbar>
                  <div class="uk-navbar-left uk-visible@m">
                     <nav>
                        <ul class="uk-navbar-nav">
                           <li><a href="index.html#"><span class="uk-margin-xsmall-right" uk-icon="icon: receiver; ratio: .75;"></span><span class="tm-pseudo">8 800 799 99 99</span></a></li>
                        </ul>
                     </nav>
                  </div>
				  <div class="uk-navbar-right">
                     <nav>
                        <ul class="uk-navbar-nav">
                           <li>Spedizioni gratuite sopra i 100 €!</li>
                        </ul>
                     </nav>
                  </div>
                  <div class="uk-navbar-right uk-visible@m">
                     <nav>
                        <ul class="uk-navbar-nav">
                           <li><a href="news.html">News</a></li>
                           <li><a href="faq.html">FAQ</a></li>
                           <li><a href="index.html#">Payment</a></li>
                        </ul>
                     </nav>
                  </div>
               </div>
            </div>
			<div class="uk-navbar-container tm-navbar-container" uk-sticky="cls-active: tm-navbar-container-fixed">
				<div class="uk-container" uk-navbar>
					<div class="uk-navbar-left">
						<button class="uk-navbar-toggle uk-hidden@m" uk-toggle="target: #nav-offcanvas" uk-navbar-toggle-icon></button><a class="uk-navbar-item uk-logo" href="<?php echo $this->baseUrl;?>"><img src="<?php echo $this->baseUrlSrc."/".FRONTEND_PATH."/Public/Img/logo.png"?>" width="90" height="32" alt="Logo"></a>
						<nav class="uk-visible@m">
							<ul class="uk-navbar-nav">
								<?php echo $menu;?>
							</ul>
						</nav>
					</div>
					<div class="uk-navbar-right">
						<a class="uk-navbar-toggle tm-navbar-button" href="index.html#" uk-search-icon></a>
							<div class="uk-navbar-dropdown uk-padding-small uk-margin-remove" uk-drop="mode: click;cls-drop: uk-navbar-dropdown;boundary: .tm-navbar-container;boundary-align: true;pos: bottom-justify;flip: x">
								<div class="uk-container">
								<div class="uk-grid-small uk-flex-middle" uk-grid>
									<div class="uk-width-expand">
										<form class="uk-search uk-search-navbar uk-width-1-1"><input class="uk-search-input" type="search" placeholder="Search…" autofocus></form>
									</div>
									<div class="uk-width-auto"><a class="uk-navbar-dropdown-close" href="index.html#" uk-close></a></div>
								</div>
								</div>
							</div>
						<a class="uk-navbar-item uk-link-muted tm-navbar-button <?php if ($islogged) { echo "uk-text-primary";?><?php } ?>" href="account.html" uk-icon="user"></a>
							<div class="uk-padding-small uk-margin-remove" uk-dropdown="pos: bottom-right; offset: -10; delay-hide: 200;" style="min-width: 250px;">
								<?php if ($islogged) { ?>
								<ul class="uk-nav uk-dropdown-nav">
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
									<li class="uk-nav-divider"></li>
									<li>
										<a href="<?php echo $this->baseUrl."/esci";?>" title="<?php echo gtext("Esci", false);?>"><?php echo gtext("Esci");?></a>
									</li>
								</ul>
								<?php } else { ?>
								<div class="uk-dropdown-nav">
									<div class="uk-text-small uk-text-right">
										<a class="uk-text-secondary uk-text-bold" href="<?php echo $this->baseUrl."/crea-account";?>"><?php echo gtext("Crea un account")?></a>
										<hr />
									</div>
									<form autocomplete="new-password" action="<?php echo $this->baseUrl."/regusers/login";?>" class="opal-login-form-ajax" data-toggle="validator" method="POST">
										<fieldset class="uk-fieldset">
											<div class="uk-margin">
												<label class="uk-form-label"><?php echo gtext("e-mail")?> *</label>
												<div class="uk-form-controls">
													<input class="uk-input " autocomplete="new-password" name="username" type="text" placeholder="<?php echo gtext("Indirizzo e-mail", false)?>" />
												</div>
											</div>
											<div class="uk-margin">
												<label class="uk-form-label"><?php echo gtext("password")?> *</label>
												<div class="uk-form-controls">
													<input class="uk-input " autocomplete="new-password" name="password" type="password" placeholder="<?php echo gtext("Password", false)?>" />
												</div>
											</div>
											
											<input autocomplete="new-password" class="uk-button uk-button-secondary uk-width-1-1" type="submit" name="" value="ACCEDI" />
										</fieldset>
									</form>
									<br />
									<a class="uk-text-small uk-text-secondary" href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
								</div>
								<?php } ?>
							</div>
						<a class="uk-navbar-item uk-link-muted tm-navbar-button" href="cart.html" uk-toggle="target: #cart-offcanvas" onclick="return false"><span uk-icon="cart"></span><span class="uk-badge">2</span></a>
					</div>
				</div>
			</div>
		</header>
         
