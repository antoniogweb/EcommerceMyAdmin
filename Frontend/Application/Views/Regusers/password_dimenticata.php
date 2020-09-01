<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <a href="<?php echo $this->baseUrl."/regusers/login";?>"><?php echo gtext("Accedi");?></a> » <?php echo gtext("Richiesta nuova password");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Richiesta nuova password");?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="site-content-contain">
	<div id="content" class="site-content">
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main">
					<article id="post-10" class="post-10 page type-page status-publish hentry">
						<div class="form-account-utente">
							<div class="woocommerce">
								<p><?php echo gtext("Inserisci l'indirizzo e-mail con il quale ti sei registrato al sito, ti invieremo una mail attraverso la quale potrai ottenere una nuova password");?></p>
	
								<?php echo $notice;?>
										
								<form action="<?php echo $this->baseUrl."/password-dimenticata";?>" method="POST">

									<div class="t">
										<input  class="text_input" type="text" name="tessera" value=""/>
									</div>
									
									<div><?php echo gtext("E-mail")?></div>
									<input class="text_input class_username" type="text" name="username" value=""/>
									<br />
									<input class="inputEntry_submit_2 button" type="submit" name="invia" value="<?php echo gtext("Richiesta nuova password");?>" title="<?php echo gtext("Richiesta nuova password");?>" />
								</form>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
