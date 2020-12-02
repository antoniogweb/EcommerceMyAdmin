<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
    

<p class="breadcrumb"><span class="testo_sei_qui">sei qui:</span> <a href="<?php echo $this->baseUrl;?>">Home</a> » News</p>

</div> <!--chiudo container superiore-->

    <div id="top" class="container">
     
    	<div id="archivio_news" class="min_height">
        
			<?php foreach ($table as $r) { ?>
        	<div class="news">
				<h2><a href="<?php echo $this->baseUrl."/dettaglio-notizia/".$r["news"]["alias"];?>"><?php echo $r["news"]["titolo"];?></a></h2>
				
				<?php if (strcmp($r["news"]["immagine"],"") !== 0) { ?>
				<div id="dettaglio_left">
					<div id="dettaglio_box_immagine">
						<div class="view second-effect">
							<a href="<?php echo $this->baseUrl."/dettaglio-notizia/".$r["news"]["alias"];?>"><img src="<?php echo $this->baseUrl."/thumb/dettaglionews/".$r["news"]["immagine"];?>" /></a>
						</div>
					</div> <!-- FINE DIV TITOLO FOTO -->    
					
                </div>
                <?php } ?>
                
                <div id="dettaglio_right">
					<p class="testo_news"><?php echo tagliaStringa($r["news"]["descrizione"],200);?></p>
					<p class="news_date"><?php echo smartDate($r["news"]["data_news"]);?></p>
					<a class="box_news_item_right_leggi_tutto" href="<?php echo $this->baseUrl."/dettaglio-notizia/".$r["news"]["alias"];?>">LEGGI</a>
                </div>
                
            </div> <!-- FINE DIV NEWS -->
            <?php } ?>
            
            <div class="page_list">
				<?php if ($rowNumber > $elementsPerPage) {?><?php echo $pageList;?><?php } ?>
			</div>
            
		</div> <!-- FINE DIV ARCHIVIO NEWS -->

        
    </div> <!-- fine div content -->