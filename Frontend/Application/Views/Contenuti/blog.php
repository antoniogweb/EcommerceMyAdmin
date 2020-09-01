<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
   <div class="container">
      <div class="wrap w-100 d-flex align-items-center text-center">
         <div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
            <div class="breadcrumb mb-0 w-100 order-last">
               <p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo $breadcrumb;?></p>
            </div>
            <div class="page-header  mb-2 w-100 order-first">
               <h1 class="page-title"><?php echo cfield($datiCategoria, "title");?></h1>
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
               <article id="post-3995" class="post-3995 page type-page status-publish hentry">
                  <div class="entry-content">
                     <div data-elementor-type="wp-post" data-elementor-id="3995" class="elementor elementor-3995 elementor-bc-flex-widget" data-elementor-settings="[]">
                        <div class="elementor-inner">
                           <div class="elementor-section-wrap">
                              <section class="elementor-element elementor-element-023941e elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="023941e" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                 <div class="elementor-container elementor-column-gap-no">
                                    <div class="elementor-row">
                                       <div class="elementor-element elementor-element-256a6fa elementor-column elementor-col-100 elementor-top-column" data-id="256a6fa" data-element_type="column">
                                          <div class="elementor-column-wrap  elementor-element-populated">
                                             <div class="elementor-widget-wrap">
                                                <div class="elementor-element elementor-element-e9c2f68 elementor-widget elementor-widget-opal-post-grid" data-id="e9c2f68" data-element_type="widget" data-widget_type="opal-post-grid.default">
                                                   <div class="elementor-widget-container">
                                                      <div class="elementor-post-wrapper">
                                                         <div class="row" data-elementor-columns="3">
															<?php foreach ($pages as $p) {
																$urlAlias = getUrlAlias($p["pages"]["id_page"]);
																$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);
															?>
                                                            <div class="column-item post-style-6">
                                                               <div class="post-inner">
                                                                  <div class="post-thumbnail">
                                                                     <a href="<?php echo $this->baseUrl."/".$urlAlias;?>">
                                                                     <img width="600" height="390" src="<?php echo $this->baseUrlSrc."/thumb/blog/".$p["pages"]["immagine"];?>" class="attachment-auros-featured-image-large size-auros-featured-image-large wp-post-image" alt="" />                </a>
                                                                  </div>
                                                                  <!-- .post-thumbnail -->
                                                                  <div class="post-content">
                                                                     <div class="entry-category ">
																		<a href="<?php echo $this->baseUrl."/".$urlAliasCategoria;?>" rel="category tag"><?php echo $p["categories"]["title"];?></a>
                                                                     </div>
                                                                     <h3 class="entry-title"><a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo field($p, "title");?></a></h3>
                                                                     <div class="entry-date">
                                                                        <div class="entry-meta-inner">
																			<span class="entry-category"><a href="<?php echo $this->baseUrl."/".$urlAliasCategoria;?>" rel="category tag">
																			<?php echo $p["categories"]["title"];?></a></span>
																			<span class="posted-on"><a href="<?php echo $this->baseUrl."/".$urlAlias;?>" rel="bookmark"><time class="entry-date published updated" datetime="<?php echo date("c", strtotime($p["pages"]["data_news"]));?>"><?php echo traduci(date("d F Y", strtotime($p["pages"]["data_news"])));?></time></a></span></div>
                                                                     </div>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <?php } ?>
                                                         </div>
															<?php if ($rowNumber > $elementsPerPage) { ?>
															<nav class="woocommerce-pagination">
																<ul class="page-numbers">
																	<?php echo $pageList;?>
																</ul>
															</nav>
															<?php } ?>
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
                  <!-- .entry-content -->
               </article>
               <!-- #post-## -->
            </main>
            <!-- #main -->
         </div>
         <!-- #primary -->
      </div>
      <!-- .wrap -->
   </div>
   <!-- #content -->
</div>
<!-- .site-content-contain -->
