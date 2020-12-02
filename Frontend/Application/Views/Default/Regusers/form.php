<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<?php if (strcmp($this->action,"add") === 0) { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <?php echo gtext("Crea un account");?></p>
					<?php } else { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <?php echo gtext("Modifica account");?></p>
					<?php } ?>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<?php if (strcmp($this->action,"add") === 0) { ?>
					<h1 class="page-title"><?php echo gtext("Crea un account");?></h1>
					<?php } else { ?>
					<h1 class="page-title"><?php echo gtext("Modifica account");?></h1>
					<?php } ?>
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
								$attiva = "account";
								
								include(tp()."/riservata-left.php");?>

								<div class="woocommerce-MyAccount-content">
									<div class="woocommerce-notices-wrapper"><?php echo $notice; ?></div>
									
									<form class="" action="<?php echo $this->baseUrl.$action;?>#main" method="POST">
										
										<?php include(tp()."/Regusers/form_dati_cliente.php");?>
										
										<?php if (strcmp($this->action,"add") === 0) { ?>
										<p class="testo_privacy"><br /><?php echo gtext("Ho letto e accettato le");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias(390);?>"><?php echo gtext("condizioni di privacy");?></a></p>

										<div class="class_accetto">
											<?php echo Html_Form::radio("accetto",$values['accetto'],array("<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("NON ACCETTO")."</span><span style='margin-right:20px;'></span>" => "non_accetto", "<span style='margin-left:8px;'></span><span class='radio_2_testo'>".gtext("ACCETTO")."</span>" => "accetto"),"radio_2");?>
										</div>
										<?php } ?>
										
										<div class="clear"></div><br /><br />
										
										<?php if (strcmp($this->action,"add") === 0) { ?>
										<p><input class="button" type="submit" name="updateAction" value="<?php echo gtext("Completa registrazione", false);?>" /></p>
										<?php } else { ?>
										<p><input class="button" type="submit" name="updateAction" value="<?php echo gtext("Modifica dati", false);?>" /></p>
										<?php } ?>
										
									</form>
								</div>
						</div><!-- .entry-content -->
					</article><!-- #post-## -->
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .wrap -->
	</div><!-- #content -->
</div><!-- .site-content-contain -->
