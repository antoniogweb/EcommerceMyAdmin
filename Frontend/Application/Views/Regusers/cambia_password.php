<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <?php echo gtext("Modifica password");?></p>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Modifica password");?></h1>
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
								<?php
								$attiva = "password";
								include(ROOT."/Application/Views/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<?php echo $notice;?>
									
									<form class="mc4wp-form" action="<?php echo $this->baseUrl."/modifica-password";?>" method="POST">
										<table width="50%">
											<tr>
												<td class="first_column"><?php echo gtext("Vecchia password");?></td>
												<td><?php echo Html_Form::password("old",$values['old'],"text_input class_old");?></td>
											</tr>
											<tr>
												<td class="first_column"><?php echo gtext("Password");?></td>
												<td><?php echo Html_Form::password("password",$values['password'],"text_input class_password");?></td>
											</tr>
											<tr>
												<td class="first_column"><?php echo gtext("Conferma password");?></td>
												<td><?php echo Html_Form::password("confirmation",$values['confirmation'],"text_input class_confirmation");?></td>
											</tr>
											<tr class="t">
												<td width="200px">Scrivi la tessera</td>
												<td><input class="login_username_input" type="text" name="tessera" value=""/></td>
											</tr>
										</table>
										
										<input class="inputEntry_submit_2 button" type="submit" name="updateAction" value="<?php echo gtext("Modifica password", false);?>" title="<?php echo gtext("Modifica password", false);?>" />
									</form>
								</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
