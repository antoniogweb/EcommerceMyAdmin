<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p class="breadcrumb"><span class="testo_sei_qui">sei qui:</span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <a href="<?php echo $this->baseUrl."/archivio-news";?>">News</a> » <?php echo $lastBreadcrumb;?></p>

</div> <!--chiudo container superiore-->

    <div id="top" class="container">
     
    	<div id="archivio_news" class="min_height">
        
			<?php foreach ($table as $r) { ?>
        	<div class="news no_row">
				<h2><?php echo $r["news"]["titolo"];?></h2>
				
				<?php if (strcmp($r["news"]["immagine"],"") !== 0) { ?>
				<div id="dettaglio_left">
					<div id="dettaglio_box_immagine">
						<div class="view second-effect">
							<img src="<?php echo $this->baseUrl."/thumb/dettaglionews/".$r["news"]["immagine"];?>" />
						</div>
					</div> <!-- FINE DIV TITOLO FOTO -->    
                </div>
                <?php } ?>
                
                <div id="dettaglio_right">
					<div><?php echo htmlentitydecode($r["news"]["descrizione"]);?></div>
					<p class="news_date"><?php echo smartDate($r["news"]["data_news"]);?></p>
                </div>
                
            </div> <!-- FINE DIV NEWS -->
            <?php } ?>
            
		</div> <!-- FINE DIV ARCHIVIO NEWS -->

        
    </div> <!-- fine div content -->