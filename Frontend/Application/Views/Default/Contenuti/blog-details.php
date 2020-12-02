<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php foreach ($pages as $p) {
	$urlAlias = getUrlAlias($p["pages"]["id_page"]);
	$urlAliasCategoria = getCategoryUrlAlias($p["categories"]["id_c"]);
?>
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
               <article id="post-1" class="post-1 post type-post status-publish format-standard has-post-thumbnail hentry category-senza-categoria">
                  <div class="post-inner">
                     <div class="post-thumbnail">
                        <img width="1000" height="700" src="<?php echo $this->baseUrlSrc."/thumb/blogdetail/".$p["pages"]["immagine"];?>" class="attachment-auros-featured-image-full size-auros-featured-image-full wp-post-image" alt="" />                                
                     </div>
                    
                     <!-- .entry-header -->
                     <div class="entry-content">
                       <?php echo htmlentitydecode(attivaModuli(field($p, "description")));?>
                     </div>
                     <!-- .entry-content -->
                     <div class="tag-social">
                        <footer class="entry-footer">
                           <div class="cat-tags-links"></div>
                        </footer>
                        <!-- .entry-footer -->
                        <div class="pbr-social-share">
                           Condividi:
                           <a class="bo-social-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $this->baseUrl."/$urlAlias";?>&title=<?php echo $p["pages"]["title"];?>" target="_blank" title="Condividi su facebook">
                           <i class="fa fa-facebook"></i>
                           </a>
                           <a class="bo-social-twitter" href="http://twitter.com/home?status=<?php echo $p["pages"]["title"];?> <?php echo $this->baseUrl."/$urlAlias";?>" target="_blank" title="Condividi su on Twitter">
                           <i class="fa fa-twitter"></i>
                           </a>
                           <a class="bo-social-tumblr" href="http://www.tumblr.com/share/link?url=
                           <?php echo $this->baseUrl."/$urlAlias";?>&name=<?php echo $p["pages"]["title"];?>" target="_blank" title="Condividi su Tumblr">
                           <i class="fa fa-tumblr"></i>
                           </a>
                        </div>
                     </div>
                  </div>
               </article>
               <!-- #post-## -->                    
               <div class="navigation">
				<div class="previous-nav">
					<?php foreach ($paginaPrecedente as $pagNav) {
						$urlAliasNav = getUrlAlias($pagNav["pages"]["id_page"]);
					?>
					<div class="thumbnail-nav"><img width="150" height="150" src="<?php echo $this->baseUrlSrc."/thumb/contenuto/".$pagNav["pages"]["immagine"];?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt=""></div>
					<div class="nav-content">
						<div class="nav-title"><?php echo gtext("Post precedente");?></div>
						<div class="nav-link"><a href="<?php echo $this->baseUrl."/$urlAliasNav";?>"><?php echo field($pagNav, "title");?></a></div>
					</div>
					<?php } ?>
				</div>
				<div class="next-nav">
					<?php foreach ($paginaSuccessiva as $pagNav) {
						$urlAliasNav = getUrlAlias($pagNav["pages"]["id_page"]);
					?>
					<div class="nav-content">
						<div class="nav-title"><?php echo gtext("Posto successivo");?></div>
						<div class="nav-link"><a href="<?php echo $this->baseUrl."/$urlAliasNav";?>"><?php echo field($pagNav, "title");?></a></div>
					</div>
					<div class="thumbnail-nav"><img width="150" height="150" src="<?php echo $this->baseUrlSrc."/thumb/contenuto/".$pagNav["pages"]["immagine"];?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt=""></div>
					<?php } ?>
				</div>
				</div>
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
