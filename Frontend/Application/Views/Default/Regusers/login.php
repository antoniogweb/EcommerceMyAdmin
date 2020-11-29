<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div id="page-title-bar" class="page-title-bar">
<div class="container">
    <div class="wrap w-100 d-flex align-items-center text-center">
        <div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
            <div class="breadcrumb mb-0 w-100 order-last">
                                    <!-- Breadcrumb NavXT 6.3.0 -->
			<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo gtext("Accedi");?></p>
			</div>
                            <div class="page-header  mb-2 w-100 order-first">
                    <h1 class="page-title">Area riservata</h1>                </div>
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
    <div class="entry-content">
        <div class="woocommerce"><div class="woocommerce-notices-wrapper"></div>

		
        <form class="woocommerce-form woocommerce-form-login login" action = '<?php echo $action;?>' method = 'POST'>
			<?php echo $notice; ?>
                        <div class="woocommerce-form-login-wrap">
                <h2>Login</h2>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="username"><?php echo gtext("Indirizzo e-mail");?><span
                                class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                           id="username" autocomplete="username"
                           value=""/>                </p>
                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                    <label for="password"><?php echo gtext("Password");?>&nbsp;<span
                                class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password"
                           id="password" autocomplete="current-password"/>
                </p>
			<button type="submit" class="woocommerce-Button button" name="login" value="Log in">Accedi</button>
            </div>

            <p class="woocommerce-LostPassword lost_password">
				<a href="<?php echo $this->baseUrl."/crea-account";?>"><?php echo gtext("Crea un account");?></a>
				
				<a class="pull-right" href="<?php echo $this->baseUrl."/password-dimenticata";?>"><?php echo gtext("Hai dimenticato la password?");?></a>
            </p>

            
        </form>

        
</div>
    </div><!-- .entry-content -->
</article><!-- #post-## -->
            </main><!-- #main -->
        </div><!-- #primary -->
            </div><!-- .wrap -->
</div><!-- #content -->
</div><!-- .site-content-contain -->
