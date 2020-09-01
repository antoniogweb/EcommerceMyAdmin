<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="notification-added-to-cart" style="display:none;">
	<div class="notification-wrap">
		<div class="ns-thumb d-inline-block">
			<img src="" alt="">
		</div>
		<div class="ns-content d-inline-block"><p><?php echo gtext("Il prodotto")?> <strong class="placeholder_prodotto_aggiunto"></strong> <?php echo gtext("è stato aggiunto al carrello")?></p></div>
	</div>
</div>
<footer id="colophon" class="site-footer">
   <div class="wrap">
      <div class="container">
         <div data-elementor-type="wp-post" data-elementor-id="362" class="elementor elementor-362 elementor-bc-flex-widget" data-elementor-settings="[]">
            <div class="elementor-inner">
               <div class="elementor-section-wrap">
                  <section class="elementor-element elementor-element-ccce7da elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="ccce7da" data-element_type="section">
                     <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-row">
                           <div class="elementor-element elementor-element-4cd3170 elementor-column elementor-col-25 elementor-top-column" data-id="4cd3170" data-element_type="column">
                              <div class="elementor-column-wrap  elementor-element-populated">
                                 <div class="elementor-widget-wrap">
									<div class="elementor-element elementor-element-86eca5e elementor-widget elementor-widget-text-editor" data-id="86eca5e" data-element_type="widget" data-widget_type="text-editor.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-text-editor elementor-clearfix">
                                             <p>
												<b><?php echo gtext("ragione sociale");?></b><br />
												<?php echo gtext("Indirizzo...");?>
                                             </p>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="elementor-element elementor-element-acee6ae elementor-widget elementor-widget-text-editor" data-id="acee6ae" data-element_type="widget" data-widget_type="text-editor.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-text-editor elementor-clearfix">
                                             <p><?php echo gtext("049 211111111");?></p>
                                          </div>
                                       </div>
                                    </div>
                                   
                                     <div class="elementor-element elementor-element-acee6ae elementor-widget elementor-widget-text-editor" data-id="acee6ae" data-element_type="widget" data-widget_type="text-editor.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-text-editor elementor-clearfix">
                                             <p><?php echo gtext("info@tttttt");?></p>
                                          </div>
                                       </div>
                                    </div>
                                     <div class="elementor-element elementor-element-acee6ae elementor-widget elementor-widget-text-editor" data-id="acee6ae" data-element_type="widget" data-widget_type="text-editor.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-text-editor elementor-clearfix">
                                             <p><?php echo gtext("p_iva_footer");?></p>
                                          </div>
                                       </div>
                                    </div>
                                    <br />
                                    <!--<div class="elementor-element elementor-element-eb8f6cb elementor-shape-square elementor-widget elementor-widget-social-icons" data-id="eb8f6cb" data-element_type="widget" data-widget_type="social-icons.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-social-icons-wrapper">
                                             <a href="" class="elementor-icon elementor-social-icon elementor-social-icon-facebook elementor-animation-pulse elementor-repeater-item-d3bc26e" target="_blank">
                                             <span class="elementor-screen-only">Facebook</span>
                                             <i class="fa fa-facebook"></i>
                                             </a>
                                             <a href="" class="elementor-icon elementor-social-icon elementor-social-icon-twitter elementor-animation-pulse elementor-repeater-item-4c628cc" target="_blank">
                                             <span class="elementor-screen-only">Twitter</span>
                                             <i class="fa fa-twitter"></i>
                                             </a>
                                             <a href="" class="elementor-icon elementor-social-icon elementor-social-icon-instagram elementor-animation-pulse elementor-repeater-item-0100f2f" target="_blank">
                                             <span class="elementor-screen-only">Instagram</span>
                                             <i class="fa fa-instagram"></i>
                                             </a>
                                             <a href="" class="elementor-icon elementor-social-icon elementor-social-icon-vk elementor-animation-pulse elementor-repeater-item-fcab8b4" target="_blank">
                                             <span class="elementor-screen-only">Vk</span>
                                             <i class="fa fa-vk"></i>
                                             </a>
                                          </div>
                                       </div>
                                    </div>-->
                                 </div>
                              </div>
                           </div>
                           <div class="elementor-element elementor-element-f3bc02a elementor-column elementor-col-25 elementor-top-column" data-id="f3bc02a" data-element_type="column">
                              <div class="elementor-column-wrap  elementor-element-populated">
                                 <div class="elementor-widget-wrap">
                                    <div class="elementor-element elementor-element-d343675 elementor-widget elementor-widget-heading" data-id="d343675" data-element_type="widget" data-widget_type="heading.default">
                                       <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default"><?php echo gtext("I nostri prodotti");?></h2>
                                       </div>
                                    </div>
                                    <div class="elementor-element elementor-element-ac45543 elementor-nav-menu__align-left elementor-nav-menu-mobile__align-center elementor-nav-menu--indicator-classic elementor-widget elementor-widget-opal-nav-menu" data-id="ac45543" data-element_type="widget" data-settings="{&quot;layout&quot;:&quot;vertical&quot;}" data-widget_type="opal-nav-menu.default">
                                       <div class="elementor-widget-container">
                                          <nav data-subMenusMinWidth="50" data-subMenusMaxWidth="500" class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-vertical e--pointer-none">
											
                                             <ul id="menu-1-ac45543" class="elementor-nav-menu sm-vertical">
												<?php foreach ($alberoCategorieProdotti as $c) {
													$cat = fullcategory($c["id_c"]);
												?>
												<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-893">
													<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($cat["categories"]["id_c"]);?>" class="elementor-item">
														<?php echo cfield($cat, "title");?>
													</a>
												</li>
												<?php } ?>
                                             </ul>
                                          </nav>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="elementor-element elementor-element-0c3aff9 elementor-column elementor-col-25 elementor-top-column" data-id="0c3aff9" data-element_type="column">
                              <div class="elementor-column-wrap  elementor-element-populated">
                                 <div class="elementor-widget-wrap">
                                    <div class="elementor-element elementor-element-5cfe51c elementor-widget elementor-widget-heading" data-id="5cfe51c" data-element_type="widget" data-widget_type="heading.default">
                                       <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Link utili</h2>
                                       </div>
                                    </div>
                                    <div class="elementor-element elementor-element-7d6a447 elementor-nav-menu__align-left elementor-nav-menu-mobile__align-center elementor-nav-menu--indicator-classic elementor-widget elementor-widget-opal-nav-menu" data-id="7d6a447" data-element_type="widget" data-settings="{&quot;layout&quot;:&quot;vertical&quot;}" data-widget_type="opal-nav-menu.default">
                                       <div class="elementor-widget-container">
                                          <nav data-subMenusMinWidth="50" data-subMenusMaxWidth="500" class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-vertical e--pointer-none">
                                             <ul id="menu-1-7d6a447" class="elementor-nav-menu sm-vertical">
													<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-882"><a href="<?php echo $this->baseUrl."/chi-siamo.html";?>" class="elementor-item">Chi siamo</a></li>
													<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-882"><a href="<?php echo $this->baseUrl."/contattaci.html";?>" class="elementor-item">Contatti</a></li>
													<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-882"><a href="<?php echo $this->baseUrl."/blog.html";?>" class="elementor-item">Blog</a></li>
													<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-882"><a href="<?php echo $this->baseUrl."/condizioni-generali-di-vendita.html";?>" class="elementor-item">Termini e condizioni</a></li>
													<li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-882"><a href="<?php echo $this->baseUrl."/cookies.html";?>" class="elementor-item">Cookies</a></li>
                                             </ul>
                                          </nav>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="elementor-element elementor-element-c67eff6 elementor-column elementor-col-25 elementor-top-column" data-id="c67eff6" data-element_type="column">
                              <div class="elementor-column-wrap  elementor-element-populated">
                                 <div class="elementor-widget-wrap">
                                    <div class="elementor-element elementor-element-9e5db64 elementor-widget elementor-widget-heading" data-id="9e5db64" data-element_type="widget" data-widget_type="heading.default">
                                       <div class="elementor-widget-container">
                                          <h2 class="elementor-heading-title elementor-size-default">Iscrivi alla newsletter</h2>
                                       </div>
                                    </div>
                                    <div class="elementor-element elementor-element-0e024ed elementor-widget elementor-widget-text-editor" data-id="0e024ed" data-element_type="widget" data-widget_type="text-editor.default">
                                       <div class="elementor-widget-container">
                                          <div id="form_newsletter_footer" class="elementor-text-editor elementor-clearfix">
												<?php include($this->viewPath("form_newsletter"));?>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <section class="elementor-element elementor-element-9983033 elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="9983033" data-element_type="section">
                     <div class="elementor-container elementor-column-gap-no">
                        <div class="elementor-row">
                           <div class="elementor-element elementor-element-04e2ab1 elementor-column elementor-col-50 elementor-top-column" data-id="04e2ab1" data-element_type="column">
                              <div class="elementor-column-wrap  elementor-element-populated">
                                 <div class="elementor-widget-wrap">
                                    <div class="elementor-element elementor-element-def8a90 elementor-widget elementor-widget-text-editor" data-id="def8a90" data-element_type="widget" data-widget_type="text-editor.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-text-editor elementor-clearfix">
                                             <p><?php echo gtext("Copyright © 2019");?> <span style="color: #222222;"><strong><?php echo gtext("XXXXX");?></strong></span>. <?php echo gtext("All rights reserved.");?></p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="elementor-element elementor-element-3f60676 elementor-column elementor-col-50 elementor-top-column" data-id="3f60676" data-element_type="column">
                              <div class="elementor-column-wrap  elementor-element-populated">
                                 <div class="elementor-widget-wrap">
                                    <div class="elementor-element elementor-element-69ea588 elementor-widget elementor-widget-image" data-id="69ea588" data-element_type="widget" data-widget_type="image.default">
                                       <div class="elementor-widget-container">
                                          <div class="elementor-image">
                                             <img width="374" height="25" src="<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/2018/10/paypal.png" class="attachment-full size-full" alt="" srcset="<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/2018/10/paypal.png 374w, <?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/2018/10/paypal-300x20.png 300w" sizes="(max-width: 374px) 100vw, 374px" />                                                            
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
   </div>
</footer>
<!-- #colophon -->
</div><!-- #page -->
</div>
</nav></div><!-- end.opal-wrapper-->
        
<div class="woocommerce-lablel-tooltip" style="display: none!important;">
   <div id="osf-woocommerce-cart">Add to cart</div>
</div>
<div class="handheld-footer-bar">
   <ul class="columns-3">
      <li class="my-account">
			<?php if ($islogged) { ?>
				<a class="my-accrount-footer" href="<?php echo $this->baseUrl."/area-riservata";?>">Area riservata</a>
			<?php } else { ?>
				<a class="my-accrount-footer" href="<?php echo $this->baseUrl."/regusers/login";?>">Login</a>     
			<?php } ?>
                            
      </li>
      <li class="my-account">
			<a class="my-wishlist-footer" href="<?php echo $this->baseUrl."/wishlist/vedi";?>">
				<i class="opal-icon-wishlist" aria-hidden="true"></i>
				
				<span class="link_wishlist_num_prod <?php if ($prodInWishlist > 0) { ?>count<?php } ?>"><?php echo $prodInWishlist ? $prodInWishlist : "";?></span>
			</a>    
      </li>
      <li class="search">
         <a class="search-footer" href="">Search</a>
         <div class="site-search">
            <form role="search" method="get" class="search-form" action="<?php echo $this->baseUrl."/risultati-ricerca";?>">
               <div class="input-group">
                  <label for="search-form-5d91a20a01a79">
                  <span class="screen-reader-text">Cerca:</span>
                  </label>
                  <input type="search" class="search-field form-control"
                     placeholder="Cerca"
                     value="" name="s"/>
                  <span class="input-group-btn">
                  <button type="submit" class="search-submit">
					<span class="opal-icon-search3"></span>
					<span class="screen-reader-text">Cerca</span>
                  </button>
                  </span>
               </div>
            </form>
         </div>
      </li>
      <li class="cart">
         <a class="footer-cart-contents" href="<?php echo $this->baseUrl."/carrello/vedi";?>" 
            title="View your shopping cart">  
         <span class="link_carrello_num_prod <?php if ($prodInCart > 0) { ?>count<?php } ?>"><?php echo $prodInCart ? $prodInCart : "";?></span>
         </a>                        
      </li>
   </ul>
</div>
				
					
		<link rel='stylesheet' id='elementor-post-2184-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-2184.css?ver=1569761186' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-1983-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-1983.css?ver=1569761186' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-2100-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-2100.css?ver=1569761186' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-2130-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-2130.css?ver=1569761186' type='text/css' media='all' />
<link rel='stylesheet' id='elementor-post-2141-css'  href='<?php echo $this->baseUrlSrc."/Public/Tema/"?>uploads/elementor/css/post-2141.css?ver=1569761186' type='text/css' media='all' />
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/libs/jquery.smartmenus.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/libs/jquery.magnific-popup.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/libs/mlpushmenu.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/libs/classie.js'></script>
<!--<script type='text/javascript'>
/* <![CDATA[ */
var wpcf7 = {"apiSettings":{"root":"","namespace":"contact-form-7\/v1"}};
/* ]]> */
</script>-->
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/contact-form-7/includes/js/scripts.js?ver=5.1.4'></script> -->
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/jquery-blockui/jquery.blockUI.min.js?ver=2.70'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var wc_add_to_cart_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%","i18n_view_cart":"View cart","cart_url":"","is_cart":"","cart_redirect_after_add":"no"};
/* ]]> */
</script>
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/frontend/add-to-cart.min.js?ver=3.7.0'></script> -->
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/js-cookie/js.cookie.min.js?ver=2.1.4'></script> -->

<?php if ($isProdotto) { ?>
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" aria-label="Chiudi"></button>
				<button class="pswp__button pswp__button--share" aria-label="Condibidi"></button>
				<button class="pswp__button pswp__button--fs" aria-label="Toggle fullscreen"></button>
				<button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
				<div class="pswp__share-tooltip"></div>
			</div>
			<button class="pswp__button pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
			<button class="pswp__button pswp__button--arrow--right" aria-label="Next (arrow right)"></button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/zoom/jquery.zoom.min.js?ver=1.7.21'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/flexslider/jquery.flexslider.min.js?ver=2.7.2'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/photoswipe/photoswipe.min.js?ver=4.1.1'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/photoswipe/photoswipe-ui-default.min.js?ver=4.1.1'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var wc_single_product_params = {"i18n_required_rating_text":"Please select a rating","review_rating_required":"yes","flexslider":{"rtl":false,"animation":"slide","smoothHeight":true,"directionNav":false,"controlNav":"thumbnails","slideshow":false,"animationSpeed":500,"animationLoop":false,"allowOneSlide":false},"zoom_enabled":"1","zoom_options":[],"photoswipe_enabled":"1","photoswipe_options":{"shareEl":false,"closeOnScroll":false,"history":false,"hideAnimationDuration":0,"showAnimationDuration":0},"flexslider_enabled":"1"};
/* ]]> */
</script>

<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/frontend/single-product.min.js?ver=3.7.0'></script>

<?php } ?>

<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/js/common.min.js?ver=2.7.3'></script> -->

<script type='text/javascript'>
/* <![CDATA[ */
var woocommerce_params = {"ajax_url":"\/wp-admin\/admin-ajax.php","wc_ajax_url":"\/?wc-ajax=%%endpoint%%"};
/* ]]> */
</script>
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/frontend/woocommerce.min.js?ver=3.7.0'></script> -->

<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/frontend/cart-fragments.min.js?ver=3.7.0'></script> -->

<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/woocommerce/assets/js/flexslider/jquery.flexslider.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>js/underscore.min.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var _wpUtilSettings = {"ajax":{"url":"\/wp-admin\/admin-ajax.php"}};
/* ]]> */
</script>
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>js/wp-util.min.js'></script> -->
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/woocommerce/main.js'></script> -->
<!-- <script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/woocommerce/single.js?ver=5.2.3'></script> -->

<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/js/theme.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>themes/auros/assets/js/sticky-layout.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>js/wp-embed.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/js/frontend-modules.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>js/jquery/ui/position.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/dialog/dialog.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/waypoints/waypoints.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/lib/swiper/swiper.min.js'></script>

<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc;?>/Public/Css/skins/minimal/minimal.css">
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/Public/Js/icheck.min.js'></script>
	
<script type='text/javascript'>
var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"version":"2.7.3","urls":{"assets":"<?php echo str_replace("/","\/",$this->baseUrlSrc)."\/Public\/Tema/"?>plugins\/elementor\/assets\/"},"settings":{"page":[],"general":{"elementor_stretched_section_container":"body","elementor_global_image_lightbox":"yes","elementor_enable_lightbox_in_editor":"yes"}},"post":{"id":204,"title":"Home 1","excerpt":""}};
</script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/elementor/assets/js/frontend.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc."/Public/Tema/"?>plugins/auros-core/assets/js/elementor/frontend.js'></script>


<?php if (!isset($_COOKIE["ok_cookie"])) { ?>
	<script>
	$ = jQuery;
	
	$(document).ready(function(){
	
		setTimeout(function(){ 
		
			$("#segnalazione_cookies_ext").animate({bottom: "0px"});
		
		}, 2000);
		
		$(".ok_cookies").click(function(e){
		
			e.preventDefault();
			
			$("#segnalazione_cookies_ext").animate({bottom: "-150px"});
			
			$.ajax({
				type: "GET",
				url: baseUrl + "/home/settacookie",
				async: true,
				cache:false,
				dataType: "html",
				success: function(content){}
			});
			
		});
		
	});
	</script>
	
	<div id="segnalazione_cookies_ext">
		<div id="segnalazione_cookies">
			<?php echo gtext("Questo sito utilizza cookie per migliorare la tua esperienza di navigazione. Cliccando su OK o continuando a navigare ne consenti l'utilizzo.");?> <b><a href="<?php echo $this->baseUrl;?>/cookies.html"><?php echo gtext("Ulteriori informazioni");?></a></b>.
			<a class="ok_cookies" title="<?php echo gtext("accetto", false);?>" href="#">OK</a>
		</div>
	</div>
	<?php } ?>

	<?php include($this->viewPath("admin"));?>
</body>
</html>
<?php
// $mysqli = Db_Mysqli::getInstance();
// print_r($mysqli->queries);
