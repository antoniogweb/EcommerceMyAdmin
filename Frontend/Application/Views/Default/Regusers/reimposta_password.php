<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <a href="<?php echo $this->baseUrl."/regusers/login";?>"><?php echo gtext("Accedi");?></a> » <a href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Richiesta nuova password");?></a> » <?php echo gtext("Imposta la password");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Imposta la password");?></h1>
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
								<?php echo $notice;?>
			
								<form action="<?php echo $this->baseUrl."/reimposta-password/$forgot_token";?>" method="POST">
									<div><?php echo gtext("Password");?></div>
									<?php echo Html_Form::password("password",$values['password'],"text_input class_password");?>
									
									<div style="margin-top:10px;"><?php echo gtext("Conferma password");?></div>
									<?php echo Html_Form::password("confirmation",$values['confirmation'],"text_input class_confirmation");?>
									
									<br />
									
									<div class="t">
										<td width="200px">Scrivi la tessera</td>
										<td><input class="login_username_input" type="text" name="tessera" value=""/></td>
									</div>
									
									<input class="inputEntry_submit_2" type="submit" name="invia" value="<?php echo gtext("Imposta la password");?>" title="<?php echo gtext("Imposta la password");?>" />
								</form>
							</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
