<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) { ?>
<div id="page-title-bar" class="page-title-bar">
   <div class="container">
      <div class="wrap w-100 d-flex align-items-center text-center">
         <div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
            <div class="breadcrumb mb-0 w-100 order-last">
               <p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo $breadcrumb;?></p>
            </div>
            <div class="page-header  mb-2 w-100 order-first">
               <h1 class="page-title"><?php echo field($p, "title");?></h1>
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
               <article id="post-1572" class="post-1572 page type-page status-publish hentry">
                  <div class="entry-content">
                     <div data-elementor-type="wp-post" data-elementor-id="1572" class="elementor elementor-1572 elementor-bc-flex-widget" data-elementor-settings="[]">
                        <div class="elementor-inner">
                           <div class="elementor-section-wrap">
                              <section class="elementor-element elementor-element-c01b0c1 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="c01b0c1" data-element_type="section">
                                 <div class="elementor-container elementor-column-gap-no">
                                    <div class="elementor-row">
                                       <div class="elementor-element elementor-element-9cb2588 elementor-column elementor-col-100 elementor-top-column" data-id="9cb2588" data-element_type="column">
                                          <div class="elementor-column-wrap  elementor-element-populated">
                                             <div class="elementor-widget-wrap">
                                                <section class="elementor-element elementor-element-40b4732 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-inner-section" data-id="40b4732" data-element_type="section">
                                                   <div class="elementor-container elementor-column-gap-no">
                                                      <div class="elementor-row">
                                                         <div class="elementor-element elementor-element-0ebf6e1 elementor-column elementor-col-100 elementor-inner-column" data-id="0ebf6e1" data-element_type="column">
                                                            <div class="elementor-column-wrap  elementor-element-populated">
                                                               <div class="elementor-widget-wrap">
                                                                  <div class="elementor-element elementor-element-c3634d6 elementor-widget elementor-widget-text-editor" data-id="c3634d6" data-element_type="widget" data-widget_type="text-editor.default">
                                                                     <div class="elementor-widget-container">
                                                                        <div class="elementor-text-editor elementor-clearfix">
                                                                          <?php echo htmlentitydecode(attivaModuli($p["pages"]["description"]));?>
                                                                          
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
<?php } ?>
