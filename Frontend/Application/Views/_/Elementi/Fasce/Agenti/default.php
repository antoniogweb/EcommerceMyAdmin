<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-background-muted">
	<div class="uk-container uk-container-medium uk-padding">
	    <div uk-grid class="uk-grid-large uk-flex uk-flex-middle">
	        <div class="uk-width-1-1">
	            <div class="">
	                <h2 class="font-2 uk-text-large uk-text-italic">
						<?php echo t("Diventa un agente")?>
	                </h2>

	                <div>
						<?php echo t("Sotto titolo fascia agente.");?>
	                    <div class="uk-margin-top uk-grid uk-child-width-1-1 uk-child-width-1-2@s" uk-grid>
							<?php if (User::$logged) { ?>
								<?php if (User::$isAgente) { ?>
								<div>
									<div class="uk-text-small uk-text-center uk-text-left@m"><?php echo gtext("Ciao");?> <strong><?php echo User::$nomeCliente;?></strong></div>
									<a href="<?php echo $this->baseUrl."/regusers/login";?>" class="uk-margin-small uk-button uk-button-primary uk-button-small uk-width-1-1"><?php echo gtext("Vai alla tua area riservata");?></a>
								</div>
								<?php } else { ?>
								<div>
									<div class="uk-text-small uk-text-center uk-text-left@m"><?php echo gtext("Ciao");?> <strong><?php echo User::$nomeCliente;?></strong>, <?php echo gtext("ti sei registrato al nostro sito, ma non sei un agente. Per diventare un agente scrivi a")?> <strong><?php echo v("email_aziendale");?></strong> <?php echo gtext("e richiedi di essere abilitato come agente.");?></div>
								</div>
								<?php } ?>
							<?php } else { ?>
	                    	<div>
	                    		<div class="uk-text-small uk-text-center uk-text-left@m"><?php echo gtext("Registrati");?></div>
	                    		<a href="<?php echo $this->baseUrl."/crea-account-agente";?>" class="uk-margin-small uk-button uk-button-default uk-button-small uk-width-1-1"><?php echo gtext("Crea un account");?></a>
	                    	</div>

	                    	<div>
	                    		<div class="uk-text-small uk-text-center uk-text-left@m">Hai già un account?</div>
	                    		<a href="<?php echo $this->baseUrl."/regusers/login";?>" class="uk-margin-small uk-button uk-button-primary uk-button-small uk-width-1-1"><?php echo gtext("Login");?></a>
	                    	</div>
	                    	<?php } ?>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>

