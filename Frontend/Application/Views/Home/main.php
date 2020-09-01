<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
</div>
<div class="site-content-contain">
   <div id="content" class="site-content">
      <div id="primary" class="content-area">
         <main id="main" class="site-main">
            <article id="post-204" class="auros-panel  post-204 page type-page status-publish hentry" >
               <div class="panel-content">
                  <div class="wrap wrap_top">
                     <header class="entry-header screen-reader-text">
                        <h2 class="entry-title">Home 1</h2>
                     </header>
                     <!-- .entry-header -->
                     <div class="entry-content">
                        <div data-elementor-type="wp-post" data-elementor-id="204" class="elementor elementor-204 elementor-bc-flex-widget" data-elementor-settings="[]">
                           <div class="elementor-inner">
                              <div class="elementor-section-wrap">
                                 <section class="elementor-element elementor-element-4a17baa elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="4a17baa" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
                                    <div class="elementor-container elementor-column-gap-no">
                                       <div class="elementor-row">
                                          <div class="elementor-element elementor-element-f8d05b3 elementor-column elementor-col-100 elementor-top-column" data-id="f8d05b3" data-element_type="column">
                                             <div class="elementor-column-wrap  elementor-element-populated">
                                                <div class="elementor-widget-wrap">
                                                   <div class="elementor-element elementor-element-5ba2039 elementor-widget elementor-widget-opal-revslider" data-id="5ba2039" data-element_type="widget" data-widget_type="opal-revslider.default">
                                                      <div class="elementor-widget-container">
                                                         <div id="rev_slider_2_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-source="woocommerce" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
                                                            <!-- START REVOLUTION SLIDER 5.4.8 auto mode -->
                                                            <div id="rev_slider_2_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.8">
                                                               <ul>
																	<?php
																	foreach ($slide as $sl) {
																		$delay = 500;
																		$layers = getLayers($sl["pages"]["id_page"]);
																	// 	print_r($layers);
																	?>
																	<!-- SLIDE  -->
																	<li data-index="rs-<?php echo $sl["pages"]["id_page"];?>" data-transition="boxfade,boxslide,slotfade-horizontal" data-slotamount="default,default,default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default,default,default" data-easeout="default,default,default" data-masterspeed="default,default,default"  data-thumb=""  data-rotate="0,0,0"  data-saveperformance="off"  data-title="<?php echo $sl["pages"]["title"];?>" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
																		<?php
																		$backgroundImage = $this->baseUrlSrc."/Public/Tema/plugins/revslider/admin/assets/images/transparent.png";
																		
																		if ($sl["pages"]["immagine"])
																			$backgroundImage = $this->baseUrlSrc."/images/contents/".$sl["pages"]["immagine"];
																		?>
																		<!-- MAIN IMAGE -->
																		<img src="<?php echo $backgroundImage;?>" data-bgcolor='#d0e8e4' style='background:#d0e8e4' alt="" title="Home 1"  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
																		<!-- LAYERS -->
																		<!-- LAYER NR. 13 -->
																		
																		<?php foreach ($layers as $layer) { ?>
																			
																			<?php if ($layer["testo"]) { ?>
																			<!-- LAYER NR. 18 -->
																			<div class="tp-caption     rev_group" 
																				id="slide-<?php echo $sl["pages"]["id_page"];?>-layer-4" 
																				data-x="['left','left','left','left']" data-hoffset="['236','70','70','30']" 
																				data-y="['top','top','top','top']" data-voffset="['329','258','676','476']" 
																				data-width="['459','574','634','420']"
																				data-height="['133','118','119','105']"
																				data-whitespace="nowrap"
																				data-type="group" 
																				data-responsive_offset="on" 
																				data-frames='[{"delay":<?php echo $delay;?>,"speed":1420,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
																				data-margintop="[0,0,0,0]"
																				data-marginright="[0,0,0,0]"
																				data-marginbottom="[0,0,0,0]"
																				data-marginleft="[0,0,0,0]"
																				data-textAlign="['inherit','inherit','inherit','inherit']"
																				data-paddingtop="[0,0,0,0]"
																				data-paddingright="[0,0,0,0]"
																				data-paddingbottom="[0,0,0,0]"
																				data-paddingleft="[0,0,0,0]"
																				style="z-index: 15; min-width: 459px; max-width: 459px; max-width: 133px; max-width: 133px; white-space: nowrap; font-size: 20px; line-height: 22px; font-weight: 400; color: #ffffff; letter-spacing: 0px;">
																				
																				<!-- LAYER NR. 21 -->
																				<div class="tp-caption   tp-resizeme" 
																				id="slide-<?php echo $sl["pages"]["id_page"];?>-layer-8" 
																				data-x="['left','left','left','left']" data-hoffset="['0','0','0','0']" 
																				data-y="['top','top','top','top']" data-voffset="['0','0','0','0']" 
																				data-fontsize="['90','70','70','48']"
																				data-lineheight="['90','70','70','70']"
																				data-letterspacing="['20','15','15','5']"
																				data-width="['none','567','567','312']"
																				data-height="['none','71','71','72']"
																				data-whitespace="nowrap"
																				data-type="text" 
																				data-responsive_offset="on" 
																				data-frames='[{"delay":"+100","speed":300,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
																				data-margintop="[0,0,0,0]"
																				data-marginright="[0,0,0,0]"
																				data-marginbottom="[0,0,0,0]"
																				data-marginleft="[0,0,0,0]"
																				data-textAlign="['inherit','inherit','inherit','inherit']"
																				data-paddingtop="[0,0,0,0]"
																				data-paddingright="[0,0,0,0]"
																				data-paddingbottom="[0,0,0,0]"
																				data-paddingleft="[0,0,0,0]"
																				style="z-index: 18; white-space: nowrap; font-size: 90px; line-height: 90px; font-weight: 700; color: #000000; letter-spacing: 20px;font-family:Eina03;text-transform:uppercase;"><?php echo htmlentitydecode($layer["testo"]);?></div>
																			</div>
																			<?php $delay += 100;?>
																			<!-- LAYER NR. 22 -->
																			<div class="tp-caption rev-btn " 
																				id="slide-<?php echo $sl["pages"]["id_page"];?>-layer-12" 
																				data-x="['left','left','left','left']" data-hoffset="['240','70','69','30']" 
																				data-y="['top','top','top','top']" data-voffset="['490','401','830','616']" 
																				data-fontsize="['16','16','14','14']"
																				data-lineheight="['55','55','44','30']"
																				data-width="none"
																				data-height="none"
																				data-whitespace="nowrap"
																				data-type="button" 
																				data-actions='[{"event":"click","action":"simplelink","target":"_self","url":"<?php echo str_replace("\\","\/",$layer["url"]);?>","delay":""}]'
																				data-responsive_offset="on" 
																				data-responsive="off"
																				data-frames='[{"delay":<?php echo $delay;?>,"speed":1170,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"y:[100%];rZ:0deg;sX:0.7;sY:0.7;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(255,255,255);bg:rgb(0,0,0);bc:rgb(0,0,0);"}]'
																				data-textAlign="['inherit','inherit','inherit','inherit']"
																				data-paddingtop="[0,0,0,0]"
																				data-paddingright="[50,50,50,25]"
																				data-paddingbottom="[0,0,0,0]"
																				data-paddingleft="[50,50,50,25]"
																				style="z-index: 5; white-space: nowrap; font-size: 16px; line-height: 55px; font-weight: 700; color: #000000; letter-spacing: 2px;font-family:Eina03;border-color:rgba(0,0,0,1);border-style:solid;border-width:2px 2px 2px 2px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;">Shop Now </div>
																			<?php } ?>
																			
																			<?php if ($layer["animazione"] == "centro") { ?>
																			<!-- Cerchio -->
																			<div class="tp-caption   tp-resizeme" 
																				id="slide-<?php echo $sl["pages"]["id_page"];?>-layer-19" 
																				data-x="['left','left','center','center']" data-hoffset="['<?php echo $layer["x_1"];?>','<?php echo $layer["x_2"];?>','<?php echo $layer["x_3"];?>','<?php echo $layer["x_4"];?>']" 
																				data-y="['top','top','top','top']" data-voffset="['<?php echo $layer["y_1"];?>','<?php echo $layer["y_2"];?>','<?php echo $layer["y_3"];?>','<?php echo $layer["y_4"];?>']" 
																				data-width="none"
																				data-height="none"
																				data-whitespace="nowrap"
																				data-type="image" 
																				data-responsive_offset="on" 
																				data-frames='[{"delay":<?php echo $delay;?>,"speed":1100,"frame":"0","from":"z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power2.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
																				data-textAlign="['inherit','inherit','inherit','inherit']"
																				data-paddingtop="[0,0,0,0]"
																				data-paddingright="[0,0,0,0]"
																				data-paddingbottom="[0,0,0,0]"
																				data-paddingleft="[0,0,0,0]"
																				style="z-index: 9;"><img src="<?php echo $this->baseUrlSrc."/images/layer/".$layer["immagine"];?>" alt="" data-ww="['auto','auto','auto','auto']" data-hh="['<?php echo $layer["larghezza_1"];?>','<?php echo $layer["larghezza_2"];?>','<?php echo $layer["larghezza_3"];?>','<?php echo $layer["larghezza_4"];?>']" data-no-retina> </div>
																			<?php } else if (!$layer["testo"]) {
																				$animazione = "";
																				switch ($layer["animazione"])
																				{
																					case "alto":
																						$animazione = '[{"delay":'.$delay.',"speed":1070,"frame":"0","from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]';
																						break;
																					case "basso":
																						$animazione = '[{"delay":'.$delay.',"speed":1070,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]';
																						break;
																					case "sinistra":
																						$animazione = '[{"delay":'.$delay.',"speed":1070,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]';
																						break;
																					case "destra":
																						$animazione = '[{"delay":'.$delay.',"speed":1070,"frame":"0","from":"x:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]';
																						break;
																				}
																			?>
																			<!-- LAYER NR. 26 -->
																			<div class="tp-caption   tp-resizeme" 
																				id="slide-<?php echo $sl["pages"]["id_page"];?>-layer-21" 
																				data-x="['left','left','left','left']" data-hoffset="['<?php echo $layer["x_1"];?>','<?php echo $layer["x_2"];?>','<?php echo $layer["x_3"];?>','<?php echo $layer["x_4"];?>']" 
																				data-y="['top','top','top','top']" data-voffset="['<?php echo $layer["y_1"];?>','<?php echo $layer["y_2"];?>','<?php echo $layer["y_3"];?>','<?php echo $layer["y_4"];?>']" 
																				data-width="none"
																				data-height="none"
																				data-whitespace="nowrap"
																				data-type="image" 
																				data-responsive_offset="on" 
																				data-frames='<?php echo $animazione;?>'
																				data-textAlign="['inherit','inherit','inherit','inherit']"
																				data-paddingtop="[0,0,0,0]"
																				data-paddingright="[0,0,0,0]"
																				data-paddingbottom="[0,0,0,0]"
																				data-paddingleft="[0,0,0,0]"
																				style="z-index: 12;"><img src="<?php echo $this->baseUrlSrc."/images/layer/".$layer["immagine"];?>" alt="" data-ww="['auto','auto','auto','auto']" data-hh="['<?php echo $layer["larghezza_1"];?>','<?php echo $layer["larghezza_2"];?>','<?php echo $layer["larghezza_3"];?>','<?php echo $layer["larghezza_4"];?>']"  data-no-retina> </div>
																			<?php } ?>
																		<?php } ?>
																	</li>
																	<?php $delay = $delay + 100; } ?>
                                                               </ul>
                                                               <div style="" class="tp-static-layers">
                                                                  <!-- LAYER NR. 39 -->
                                                                  <div class="tp-caption   tp-resizeme  slide-status-numbers tp-static-layer" 
                                                                     id="slider-2-layer-30" 
                                                                     data-x="['right','right','right','right']" data-hoffset="['-20','-20','-20','-20']" 
                                                                     data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']" 
                                                                     data-width="none"
                                                                     data-height="none"
                                                                     data-whitespace="nowrap"
                                                                     data-type="text" 
                                                                     data-responsive_offset="on" 
                                                                     data-startslide="0" 
                                                                     data-endslide="2" 
                                                                     data-frames='[{"delay":10,"speed":300,"frame":"0","from":"opacity:0;","to":"o:1;rZ:-90;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                                                     data-textAlign="['inherit','inherit','inherit','inherit']"
                                                                     data-paddingtop="[0,0,0,0]"
                                                                     data-paddingright="[0,0,0,0]"
                                                                     data-paddingbottom="[0,0,0,0]"
                                                                     data-paddingleft="[0,0,0,0]"
                                                                     style="z-index: 13; white-space: nowrap; font-size: 30px; line-height: 20px; font-weight: 700; color: #000000; letter-spacing: 0px;font-family:Eina03;border-color:rgb(235,112,37);border-style:solid;border-width:0px 0px 0px 75px;text-indent:-10px;">1 / 3 </div>
                                                               </div>
                                                               <script>var htmlDiv = document.getElementById("rs-plugin-settings-inline-css"); var htmlDivCss="";
                                                                  if(htmlDiv) {
                                                                  	htmlDiv.innerHTML = htmlDiv.innerHTML + htmlDivCss;
                                                                  }else{
                                                                  	var htmlDiv = document.createElement("div");
                                                                  	htmlDiv.innerHTML = "<style>" + htmlDivCss + "</style>";
                                                                  	document.getElementsByTagName("head")[0].appendChild(htmlDiv.childNodes[0]);
                                                                  }
                                                               </script>
                                                               <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
                                                            </div>
                                                            <script>var htmlDiv = document.getElementById("rs-plugin-settings-inline-css"); var htmlDivCss="";
                                                               if(htmlDiv) {
                                                               	htmlDiv.innerHTML = htmlDiv.innerHTML + htmlDivCss;
                                                               }else{
                                                               	var htmlDiv = document.createElement("div");
                                                               	htmlDiv.innerHTML = "<style>" + htmlDivCss + "</style>";
                                                               	document.getElementsByTagName("head")[0].appendChild(htmlDiv.childNodes[0]);
                                                               }
                                                            </script>
                                                            <script type="text/javascript">
                                                               if (setREVStartSize!==undefined) setREVStartSize(
                                                               	{c: '#rev_slider_2_1', responsiveLevels: [1240,1024,778,480], gridwidth: [1710,1024,778,480], gridheight: [860,768,960,720], sliderLayout: 'auto'});
                                                               			
                                                               var revapi2,
                                                               	tpj;	
                                                               (function() {			
                                                               	if (!/loaded|interactive|complete/.test(document.readyState)) document.addEventListener("DOMContentLoaded",onLoad); else onLoad();	
                                                               	function onLoad() {				
                                                               		if (tpj===undefined) { tpj = jQuery; if("off" == "on") tpj.noConflict();}
                                                               	if(tpj("#rev_slider_2_1").revolution == undefined){
                                                               		revslider_showDoubleJqueryError("#rev_slider_2_1");
                                                               	}else{
                                                               		revapi2 = tpj("#rev_slider_2_1").show().revolution({
                                                               			sliderType:"standard",
                                                               			jsFileLocation:"<?php echo $this->baseUrlSrc."/Public/Tema/"?>/plugins/revslider/public/assets/js/",
                                                               			sliderLayout:"auto",
                                                               			dottedOverlay:"none",
                                                               			delay:9000,
                                                               			navigation: {
                                                               				keyboardNavigation:"off",
                                                               				keyboard_direction: "horizontal",
                                                               				mouseScrollNavigation:"off",
                                                                							mouseScrollReverse:"default",
                                                               				onHoverStop:"off",
                                                               				arrows: {
                                                               					style:"auros_1",
                                                               					enable:true,
                                                               					hide_onmobile:true,
                                                               					hide_under:992,
                                                               					hide_onleave:false,
                                                               					tmp:'',
                                                               					left: {
                                                               						h_align:"right",
                                                               						v_align:"bottom",
                                                               						h_offset:91,
                                                               						v_offset:0
                                                               					},
                                                               					right: {
                                                               						h_align:"right",
                                                               						v_align:"bottom",
                                                               						h_offset:0,
                                                               						v_offset:0
                                                               					}
                                                               				}
                                                               			},
                                                               			responsiveLevels:[1240,1024,778,480],
                                                               			visibilityLevels:[1240,1024,778,480],
                                                               			gridwidth:[1710,1024,778,480],
                                                               			gridheight:[860,768,960,720],
                                                               			lazyType:"none",
                                                               			shadow:0,
                                                               			spinner:"spinner0",
                                                               			stopLoop:"off",
                                                               			stopAfterLoops:-1,
                                                               			stopAtSlide:-1,
                                                               			shuffle:"off",
                                                               			autoHeight:"off",
                                                               			disableProgressBar:"on",
                                                               			hideThumbsOnMobile:"off",
                                                               			hideSliderAtLimit:0,
                                                               			hideCaptionAtLimit:0,
                                                               			hideAllCaptionAtLilmit:0,
                                                               			debugMode:false,
                                                               			fallbacks: {
                                                               				simplifyAll:"off",
                                                               				nextSlideOnWindowFocus:"off",
                                                               				disableFocusListener:false,
                                                               			}
                                                               		});
                                                               var api = revapi2,
                                                               	divider = ' / ',
                                                                   totalSlides,
                                                                   numberText;
                                                               
                                                               api.one('revolution.slide.onloaded', function() {
                                                                   
                                                               	totalSlides = api.revmaxslide();
                                                               	numberText = api.find('.slide-status-numbers').text('1' + divider + totalSlides);
                                                               
                                                                   api.on('revolution.slide.onbeforeswap', function(e, data) {
                                                               
                                                                       numberText.text((data.nextslide.index() + 1) + divider + totalSlides);
                                                                   
                                                                   });
                                                                   
                                                               });	}; /* END OF revapi call */
                                                               	
                                                                }; /* END OF ON LOAD FUNCTION */
                                                               }()); /* END OF WRAPPING FUNCTION */
                                                            </script>
                                                            <script>
                                                               var htmlDivCss = unescape("%40media%20%28min-width%3A%201700px%29%7B%0A%20%20.tp-static-layers%20.tp-parallax-wrap%20%7B%20%20%20%20%20%20%20%0A%20%20%20%20left%3A%201600px%21important%3B%0A%20%20%7D%0A%20%20%0A%7D%0A%0A%40media%20%28min-width%3A%201780px%29%7B%0A%20%20.tp-static-layers%20.tp-parallax-wrap%20%7B%20%20%20%20%20%20%20%0A%20%20%20%20left%3A%201670px%21important%3B%0A%20%20%7D%0A%20%20%0A%7D%0A%40media%20%28min-width%3A%201830px%29%7B%0A%20%20.tp-static-layers%20.tp-parallax-wrap%20%7B%20%20%20%20%20%20%20%0A%20%20%20%20left%3A%201700px%21important%3B%0A%20%20%7D%0A%20%20%0A%7D");
                                                               var htmlDiv = document.getElementById('rs-plugin-settings-inline-css');
                                                               if(htmlDiv) {
                                                               	htmlDiv.innerHTML = htmlDiv.innerHTML + htmlDivCss;
                                                               }
                                                               else{
                                                               	var htmlDiv = document.createElement('div');
                                                               	htmlDiv.innerHTML = '<style>' + htmlDivCss + '</style>';
                                                               	document.getElementsByTagName('head')[0].appendChild(htmlDiv.childNodes[0]);
                                                               }
                                                                
                                                            </script><script>
                                                               var htmlDivCss = unescape("%23rev_slider_2_1%20.auros_1.tparrows%20%7B%0A%09cursor%3Apointer%3B%0A%09background%3Argba%28255%2C%20255%2C%20255%2C%201%29%3B%0A%09width%3A90px%3B%0A%09height%3Acalc%2890px%20-%2010px%29%3B%0A%09position%3Aabsolute%3B%0A%09display%3Ablock%3B%0A%09z-index%3A100%3B%0A%7D%0A%23rev_slider_2_1%20.auros_1.tparrows%3Ahover%3Abefore%20%7B%0A%09color%3A%20rgb%280%2C%200%2C%200%2C%201%29%3B%0A%7D%0A%23rev_slider_2_1%20.auros_1.tparrows%3Abefore%20%7B%0A%09font-family%3A%20%22revicons%22%3B%0A%09font-size%3A34px%3B%0A%09color%3A%20rgb%28204%2C%20204%2C%20204%29%3B%0A%09display%3Ablock%3B%0A%09line-height%3A%20calc%2890px%20-%2010px%29%3B%0A%09text-align%3A%20center%3B%0A%7D%0A%23rev_slider_2_1%20.auros_1.tparrows.tp-leftarrow%3Abefore%20%7B%0A%09content%3A%20%22%5Ce824%22%3B%0A%7D%0A%23rev_slider_2_1%20.auros_1.tparrows.tp-rightarrow%3Abefore%20%7B%0A%09content%3A%20%22%5Ce825%0A%22%3B%0A%7D%0A%0A%0A");
                                                               var htmlDiv = document.getElementById('rs-plugin-settings-inline-css');
                                                               if(htmlDiv) {
                                                               	htmlDiv.innerHTML = htmlDiv.innerHTML + htmlDivCss;
                                                               }
                                                               else{
                                                               	var htmlDiv = document.createElement('div');
                                                               	htmlDiv.innerHTML = '<style>' + htmlDivCss + '</style>';
                                                               	document.getElementsByTagName('head')[0].appendChild(htmlDiv.childNodes[0]);
                                                               }
                                                                
                                                            </script>
                                                         </div>
                                                         <!-- END REVOLUTION SLIDER -->		
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </section>
								<section class="elementor-element elementor-element-429762f elementor-section-stretched elementor-section-content-middle animated-fast elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible elementor-section elementor-top-section" data-id="429762f" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;animation&quot;:&quot;opal-move-up&quot;}">
									<div class="elementor-container elementor-column-gap-no">
										<div class="elementor-row">
											<div class="elementor-element elementor-element-7c3fed3 elementor-column elementor-col-100 elementor-top-column" data-id="7c3fed3" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
												<div class="elemento_sotto_slide elementor-column-wrap  elementor-element-populated">
													<div class="elementor-widget-wrap">
													<div class="elementor-element elementor-element-9bdd9c0 elementor-widget elementor-widget-opal-testimonials" data-id="9bdd9c0" data-element_type="widget" data-widget_type="opal-testimonials.default">
														<div class="elementor-widget-container">
															<div class="elementor-testimonial-wrapper layout_2 elementor-testimonial-text-align-center">
																<div class="row" data-elementor-columns="1">
																<div class="elementor-testimonial-item column-item">
																	<div class="elementor-testimonial-image">
																	</div>
																	<div class="elementor-testimonial-inline">
																		<p><?php echo testo("Testo da modificare");?></p>
																	</div>
																</div>
																</div>
															</div>
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</section>
                                                   
								<section class="sezione_famiglie elementor-element elementor-element-9b653bb elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="9b653bb" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
									<div class="elementor-container elementor-column-gap-no">
										<div class="elementor-row">
											<?php foreach ($marchi as $m) { ?>
											<div class="elementor-element elementor-element-22815ff animated-fast elementor-invisible elementor-column elementor-col-50 elementor-top-column" data-id="22815ff" data-element_type="column" data-settings="{&quot;animation&quot;:&quot;opal-move-up&quot;}">
												<div class="elementor-column-wrap  elementor-element-populated">
													<div class="elementor-widget-wrap">
													<div class="elementor-element elementor-element-bbc84df elementor-vertical-align-flex-end elementor-widget elementor-widget-opal-product-categories" data-id="bbc84df" data-element_type="widget" data-widget_type="opal-product-categories.default">
														<div class="elementor-widget-container">
															<div class="product-cats">
																<div class="cats-image">
																<img src="<?php echo $this->baseUrlSrc."/thumb/famiglia/".$m["immagine"];?>"
																	alt="<?php echo $m["titolo"];?>">
																</div>
																<a style="display:block;" href="<?php echo $this->baseUrl."/".encodeUrl(strtolower($m["titolo"]))."/".$categoriaShop["alias"].".html";?>"
																	title="<?php echo $m["titolo"];?>"><div class="product-cats-meta">
																	
																</div></a>
															</div>
															<div class="cats-title">
																<a href="<?php echo $this->baseUrl."/".encodeUrl(strtolower($m["titolo"]))."/".$categoriaShop["alias"].".html";?>"
																	title="<?php echo $m["titolo"];?>">
																	<?php echo $m["titolo"];?>
																</a>
															</div>
															<div class="cats-total">
																<?php echo prodottiMarchio($m["id_marchio"]);?> prodotti                    
															</div>
														</div>
													</div>
													</div>
												</div>
											</div>
											<?php } ?>
										</div>
									</div>
								</section>
								
								<section class="home_in_evidenza elementor-element elementor-element-163c072 animated-fast elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible elementor-section elementor-top-section" data-id="163c072" data-element_type="section" data-settings="{&quot;animation&quot;:&quot;opal-move-up&quot;}">
									<div class="elementor-container elementor-column-gap-no">
										<div class="elementor-row">
											<div class="elementor-element elementor-element-fbdeb74 elementor-column elementor-col-100 elementor-top-column" data-id="fbdeb74" data-element_type="column">
												<div class="elementor-column-wrap  elementor-element-populated">
													<div class="elementor-widget-wrap">
													<div class="elementor-element elementor-element-0eb223f elementor-widget elementor-widget-heading" data-id="0eb223f" data-element_type="widget" data-widget_type="heading.default">
														<div class="elementor-widget-container">
															<h2 class="elementor-heading-title elementor-size-default">Prodotti in evidenza</h2>
														</div>
													</div>
													<div class="elementor-element elementor-element-7937c6f elementor-widget elementor-widget-text-editor" data-id="7937c6f" data-element_type="widget" data-widget_type="text-editor.default">
														<div class="elementor-widget-container">
															<div class="elementor-text-editor elementor-clearfix">
																<p><?php echo gtext("testo home in evidenza da modificare");?></p>
															</div>
														</div>
													</div>
													<div class="elementor-element elementor-element-ff43d76 animated-fast elementor-product-style-1 elementor-invisible elementor-widget elementor-widget-opal-products" data-id="ff43d76" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;opal-move-up&quot;}" data-widget_type="opal-products.default">
														<div class="elementor-widget-container">
															<div >
																<div class="woocommerce columns-4 ">
																<ul class="products columns-4">
																		<?php
																		$pages = $prodottiInEvidenza;
																		include(ROOT."/Application/Views/Contenuti/Elementi/Categorie/prodotti.php");
																		?>
																</ul>
																</div>
															</div>
														</div>
													</div>
													<div class="elementor-element elementor-element-a84463f elementor-align-center animated-fast elementor-invisible elementor-widget elementor-widget-button" data-id="a84463f" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;opal-move-up&quot;}" data-widget_type="button.default">
														<div class="elementor-widget-container">
															<div class="elementor-button-wrapper">
																<a href="<?php echo $this->baseUrl."/".$categoriaShop["alias"].".html";?>" class="elementor-button-link elementor-button elementor-size-sm elementor-animation-grow" role="button">
																<span class="elementor-button-content-wrapper">
																<span class="elementor-button-text">+ Vedi tutti i prodotti</span>
																</span>
																</a>
															</div>
														</div>
													</div>
													<!--<div class="elementor-element elementor-element-55dc090 elementor-widget elementor-widget-divider" data-id="55dc090" data-element_type="widget" data-widget_type="divider.default">
														<div class="elementor-widget-container">
															<div class="elementor-divider">
																<span class="elementor-divider-separator">
																</span>
															</div>
														</div>
													</div>-->
													</div>
												</div>
											</div>
										</div>
									</div>
								</section>
								
								<section class="elementor-element elementor-element-429762f elementor-section-stretched elementor-section-content-middle animated-fast elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible elementor-section elementor-top-section" data-id="429762f" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;animation&quot;:&quot;opal-move-up&quot;}">
                                    <div class="elementor-container elementor-column-gap-no">
                                       <div class="elementor-row">
                                          <div class="elementor-element elementor-element-7c3fed3 elementor-column elementor-col-100 elementor-top-column" data-id="7c3fed3" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                                             <div class="elementor-column-wrap  elementor-element-populated">
                                                <div class="elementor-widget-wrap">
                                                   <div class="elementor-element elementor-element-9bdd9c0 elementor-widget elementor-widget-opal-testimonials" data-id="9bdd9c0" data-element_type="widget" data-widget_type="opal-testimonials.default">
                                                      <div class="elementor-widget-container">
                                                         <div class="elementor-testimonial-wrapper layout_2 elementor-testimonial-text-align-center">
                                                            <div class="row" data-elementor-columns="1">
                                                               <div class="elementor-testimonial-item column-item">
                                                                  <div class="elementor-testimonial-image">
                                                                  </div>
                                                                  <div class="elementor-testimonial-inline">
                                                                     <div class="elementor-testimonial-content">
                                                                        <?php echo gtext("Very good Design. Flexible. Fast Support."); ?>                                       
                                                                     </div>
                                                                     <div class="elementor-testimonial-meta-inner">
                                                                        <div class="elementor-testimonial-details">
                                                                           <div class="elementor-testimonial-name"><?php echo gtext("Steve John."); ?></div>
                                                                           <div class="elementor-testimonial-job"><?php echo gtext("(customer)"); ?></div>
                                                                        </div>
                                                                     </div>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </section>
                                 
								<section class="elementor-element elementor-element-27aa53d animated-fast elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible elementor-section elementor-top-section" data-id="27aa53d" data-element_type="section" data-settings="{&quot;animation&quot;:&quot;opal-move-up&quot;}">
									<div class="elementor-container elementor-column-gap-no">
										<div class="elementor-row">
											<div class="elementor-element elementor-element-e1bbe6b elementor-column elementor-col-100 elementor-top-column" data-id="e1bbe6b" data-element_type="column">
												<div class="elementor-column-wrap  elementor-element-populated">
													<div class="elementor-widget-wrap">
													<div class="elementor-element elementor-element-44b14e4 elementor-widget elementor-widget-heading" data-id="44b14e4" data-element_type="widget" data-widget_type="heading.default">
														<div class="elementor-widget-container">
															<h2 class="elementor-heading-title elementor-size-default"><?php echo gtext("Nuovo Design");?></h2>
														</div>
													</div>
													<div class="elementor-element elementor-element-b023ca9 elementor-tabs-view-horizontal elementor-widget elementor-widget-opal-tabs" data-id="b023ca9" data-element_type="widget" data-widget_type="opal-tabs.default">
														<div class="elementor-widget-container">
															<div class="elementor-tabs" role="tablist">
																<div class="elementor-tabs-wrapper">
																<?php
																$index = 0;
																foreach ($alberoCategorieProdottiConShop as $c) {
																	$cat = fullcategory($c["id_c"]);
																	
																	if ($cat["categories"]["mostra_in_home"] == "N")
																		continue;
																?>
																<div id="elementor-tab-title-<?php echo $c["id_c"];?>" class="elementor-tab-title elementor-tab-desktop-title <?php if ($index == 0) { ?>elementor-active<?php } ?>" data-tab="2" tabindex="1842" role="tab" aria-controls="elementor-tab-content-<?php echo $c["id_c"];?>"><?php echo ucfirst(cfield($cat, "title"));?></div>
																<?php $index++; } ?>
																</div>
																<div class="elementor-tabs-content-wrapper">
																	<?php
																	$pModel = new PagesModel();
																	$cModel = new CategoriesModel();
																	$index = 0;
																	foreach ($alberoCategorieProdottiConShop as $c) {
																		if ($c["mostra_in_home"] == "N")
																			continue;
																		
																		$childrenProdotti = $cModel->children($c["id_c"], true);
																		
																		$prodottiInEvidenzaCat = getRandom($pModel->clear()->inner("categories")->on("categories.id_c = pages.id_c")->where(array(
																			"in" => array("-id_c" => $childrenProdotti),
																			"attivo"=>"Y",
																			"in_evidenza"=>"N",
																		))->orderBy("pages.id_order desc")->send(), 4);
																	?>
																	<div id="elementor-tab-content-<?php echo $c["id_c"];?>" class="elementor-tab-content elementor-clearfix <?php if ($index == 0) { ?>elementor-active<?php } ?>" data-tab="1" role="tabpanel" aria-labelledby="elementor-tab-title-1841" >
																		<div data-elementor-type="page" data-elementor-id="2184" class="elementor elementor-2184 elementor-bc-flex-widget" data-elementor-settings="[]" >
																			<div class="elementor-inner">
																				<div class="elementor-section-wrap">
																				<section class="elementor-element elementor-element-6c3d3e8 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="6c3d3e8" data-element_type="section">
																					<div class="elementor-container elementor-column-gap-no">
																						<div class="elementor-row">
																							<div class="elementor-element elementor-element-de98d3b elementor-column elementor-col-50 elementor-top-column" data-id="de98d3b" data-element_type="column">
																							<div class="elementor-column-wrap  elementor-element-populated">
																								<div class="elementor-widget-wrap">
																									<div class="elementor-element elementor-element-5edc612 elementor-vertical-align-flex-end elementor-widget elementor-widget-opal-product-categories" data-id="5edc612" data-element_type="widget" data-widget_type="opal-product-categories.default">
																										<div class="elementor-widget-container">
																										<div class="product-cats">
																											<div class="cats-image">
																												<img src="<?php echo $this->baseUrlSrc."/thumb/categoria/".$c["immagine"];?>"
																													alt="All">
																											</div>
																											<div class="product-cats-meta">
																												<div class="cats-title">
																													<a href="<?php echo $this->baseUrl."/".getCategoryUrlAlias($c["id_c"]);?>"
																													title="All">
																													Vedi tutti                        </a>
																												</div>
																												<!--<div class="cats-total">
																													17 items                    
																												</div>-->
																											</div>
																										</div>
																										</div>
																									</div>
																								</div>
																							</div>
																							</div>
																							<div class="elementor-element elementor-element-2d91c87a elementor-column elementor-col-50 elementor-top-column" data-id="2d91c87a" data-element_type="column">
																							<div class="elementor-column-wrap  elementor-element-populated">
																								<div class="elementor-widget-wrap">
																									<div class="elementor-element elementor-element-2ff9ec55 elementor-product-style-1 elementor-widget elementor-widget-opal-products" data-id="2ff9ec55" data-element_type="widget" data-widget_type="opal-products.default">
																										<div class="elementor-widget-container">
																										<div >
																											<div class="woocommerce columns-2 ">
																												<ul class="products columns-2">
																													<?php
																													foreach ($prodottiInEvidenzaCat as $p) {
																														include(ROOT."/Application/Views/Contenuti/Elementi/Categorie/prodotto.php");
																													}
																													?>
																												</ul>
																											</div>
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
																	<?php $index++; } ?>
																
																</div>
															</div>
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</section>
								<?php if (false) { ?>
                                 <section class="elementor-element elementor-element-f1a95cb elementor-section-stretched animated-fast elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible elementor-section elementor-top-section" data-id="f1a95cb" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;animation&quot;:&quot;opal-move-up&quot;}">
                                    <div class="elementor-container elementor-column-gap-no">
                                       <div class="elementor-row">
                                          <div class="elementor-element elementor-element-5283b52 elementor-column elementor-col-100 elementor-top-column" data-id="5283b52" data-element_type="column">
                                             <div class="elementor-column-wrap  elementor-element-populated">
                                                <div class="elementor-widget-wrap">
                                                   <div class="elementor-element elementor-element-8ae6ed1 elementor-widget elementor-widget-opal-revslider" data-id="8ae6ed1" data-element_type="widget" data-widget_type="opal-revslider.default">
                                                      <div class="elementor-widget-container">
                                                         <div id="rev_slider_1_2_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
                                                            <!-- START REVOLUTION SLIDER 5.4.8 auto mode -->
                                                            <div id="rev_slider_1_2" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.8">
                                                               <ul>
																	<?php foreach ($slidesotto as $sl) {
																		$urlAlias = getUrlAlias($sl["pages"]["id_page"]);
																	?>
																	<!-- SLIDE  -->
																	<li data-index="rs-<?php echo $sl["pages"]["id_page"];?>" data-transition="slotfade-horizontal,fadefromtop,fadefromleft,fadefromright,fadefrombottom" data-slotamount="default,default,default,default,default" data-hideafterloop="0" data-hideslideonmobile="off"  data-easein="default,default,default,default,default" data-easeout="default,default,default,default,default" data-masterspeed="default,default,default,default,default"  data-rotate="0,0,0,0,0"  data-saveperformance="off"  data-title="Slide" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
																		<!-- MAIN IMAGE -->
																		<img src="<?php echo $this->baseUrlSrc."/thumb/slidesotto/".$sl["pages"]["immagine"];?>"  alt="" title="Home 1"  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="off" class="rev-slidebg" data-no-retina>
																		<!-- LAYERS -->
																		<!-- LAYER NR. 1 -->
																		<div class="tp-caption     rev_group rs-parallaxlevel-2" 
																			id="slide-1-layer-3" 
																			data-x="['left','left','left','left']" data-hoffset="['1111','537','370','195']" 
																			data-y="['top','top','top','top']" data-voffset="['119','146','161','72']" 
																			data-width="135"
																			data-height="38"
																			data-whitespace="nowrap"
																			data-type="group" 
																			data-responsive_offset="on" 
																			data-frames='[{"delay":160,"speed":1020,"frame":"0","from":"x:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
																			data-margintop="[0,0,0,0]"
																			data-marginright="[0,0,0,0]"
																			data-marginbottom="[0,0,0,0]"
																			data-marginleft="[0,0,0,0]"
																			data-textAlign="['inherit','inherit','inherit','inherit']"
																			data-paddingtop="[0,0,0,0]"
																			data-paddingright="[0,0,0,0]"
																			data-paddingbottom="[0,0,0,0]"
																			data-paddingleft="[0,0,0,0]"
																			style="z-index: 5; min-width: 135px; max-width: 135px; max-width: 38px; max-width: 38px; white-space: nowrap; font-size: 20px; line-height: 22px; font-weight: 400; color: #ffffff; letter-spacing: 0px;border-color:rgb(255,255,255);border-style:solid;border-width:0px 0px 0px 1px;">
																			<!-- LAYER NR. 2 -->
																			<div class="tp-caption   tp-resizeme rs-parallaxlevel-2" 
																			id="slide-1-layer-6" 
																			data-x="['left','left','left','left']" data-hoffset="['15','15','15','15']" 
																			data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']" 
																			data-width="100"
																			data-height="none"
																			data-whitespace="normal"
																			data-type="text" 
																			data-responsive_offset="on" 
																			data-frames='[{"delay":"+300","speed":660,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
																			data-margintop="[0,0,0,0]"
																			data-marginright="[0,0,0,0]"
																			data-marginbottom="[0,0,0,0]"
																			data-marginleft="[0,0,0,0]"
																			data-textAlign="['inherit','inherit','inherit','inherit']"
																			data-paddingtop="[0,0,0,0]"
																			data-paddingright="[0,0,0,0]"
																			data-paddingbottom="[0,0,0,0]"
																			data-paddingleft="[0,0,0,0]"
																			style="z-index: 6; min-width: 100px; max-width: 100px; white-space: normal; font-size: 15px; line-height: 20px; font-weight: 600; color: #ffffff; letter-spacing: px;font-family:Eina03;text-transform:uppercase;"><?php echo $sl["pages"]["title"];?> </div>
																		</div>
																		<!-- LAYER NR. 3 -->
																		<div class="tp-caption   tp-resizeme rs-parallaxlevel-8" 
																			id="slide-1-layer-14" 
																			data-x="['left','left','left','left']" data-hoffset="['1113','541','369','196']" 
																			data-y="['top','top','top','top']" data-voffset="['214','228','218','149']" 
																			data-width="none"
																			data-height="none"
																			data-whitespace="nowrap"
																			data-type="image" 
																			data-responsive_offset="on" 
																			data-frames='[{"delay":740,"speed":1010,"frame":"0","from":"x:[175%];y:0px;z:0;rX:0;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:1;","mask":"x:[-100%];y:0;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
																			data-textAlign="['inherit','inherit','inherit','inherit']"
																			data-paddingtop="[0,0,0,0]"
																			data-paddingright="[0,0,0,0]"
																			data-paddingbottom="[0,0,0,0]"
																			data-paddingleft="[0,0,0,0]"
																			style="z-index: 7;"><img src="<?php echo $this->baseUrlSrc."/thumb/slidesottothumb/".$sl["pages"]["immagine_2"];?>" alt="" data-ww="['242px','242px','242px','242px']" data-hh="['259px','259px','259px','259px']" width="242" height="259" data-no-retina> </div>
																		<!-- LAYER NR. 4 -->
																		<div class="tp-caption rev-btn  tp-resizeme rs-parallaxlevel-8" 
																			id="slide-1-layer-12" 
																			data-x="['left','left','left','left']" data-hoffset="['1108','538','364','197']" 
																			data-y="['top','top','top','top']" data-voffset="['548','541','538','462']" 
																			data-lineheight="['52','48','48','42']"
																			data-width="none"
																			data-height="none"
																			data-whitespace="nowrap"
																			data-type="button" 
																			data-actions='[{"event":"click","action":"simplelink","target":"_self","url":"<?php echo str_replace("\\","\/",$sl["pages"]["url"]);?>","delay":""}]'
																			data-responsive_offset="on" 
																			data-frames='[{"delay":1000,"speed":1170,"frame":"0","from":"z:0;rX:0deg;rY:0;rZ:0;sX:2;sY:2;skX:0;skY:0;opacity:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power2.easeOut"},{"delay":"wait","speed":300,"frame":"999","to":"y:[100%];rZ:0deg;sX:0.7;sY:0.7;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;","style":"c:rgb(255,255,255);bg:rgb(0,0,0);bc:rgb(0,0,0);"}]'
																			data-textAlign="['inherit','inherit','inherit','inherit']"
																			data-paddingtop="[0,0,0,0]"
																			data-paddingright="[50,40,35,30]"
																			data-paddingbottom="[0,0,0,0]"
																			data-paddingleft="[50,40,35,30]"
																			style="z-index: 9; white-space: nowrap; font-size: 14px; line-height: 52px; font-weight: 600; color: #ffffff; letter-spacing: 2px;font-family:Eina03;text-transform:uppercase;border-color:rgb(255,255,255);border-style:solid;border-width:2px 2px 2px 2px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;"><?php echo gtext("Scopri", false)?></div>
																	</li>
																	<?php } ?>
                                                               </ul>
                                                               <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
                                                            </div>
                                                            <script type="text/javascript">
                                                               if (setREVStartSize!==undefined) setREVStartSize(
                                                               	{c: '#rev_slider_1_2', responsiveLevels: [1240,1024,778,480], gridwidth: [1710,1024,778,480], gridheight: [700,768,700,600], sliderLayout: 'auto'});
                                                               			
                                                               var revapi1,
                                                               	tpj;	
                                                               (function() {			
                                                               	if (!/loaded|interactive|complete/.test(document.readyState)) document.addEventListener("DOMContentLoaded",onLoad); else onLoad();	
                                                               	function onLoad() {				
                                                               		if (tpj===undefined) { tpj = jQuery; if("off" == "on") tpj.noConflict();}
                                                               	if(tpj("#rev_slider_1_2").revolution == undefined){
                                                               		revslider_showDoubleJqueryError("#rev_slider_1_2");
                                                               	}else{
                                                               		revapi1 = tpj("#rev_slider_1_2").show().revolution({
                                                               			sliderType:"standard",
                                                               			jsFileLocation:"<?php echo $this->baseUrlSrc."/Public/Tema/"?>/plugins/revslider/public/assets/js/",
                                                               			sliderLayout:"auto",
                                                               			dottedOverlay:"none",
                                                               			delay:9000,
                                                               			navigation: {
                                                               				onHoverStop:"off",
                                                               			},
                                                               			responsiveLevels:[1240,1024,778,480],
                                                               			visibilityLevels:[1240,1024,778,480],
                                                               			gridwidth:[1710,1024,778,480],
                                                               			gridheight:[700,768,700,600],
                                                               			lazyType:"none",
                                                               			parallax: {
                                                               				type:"mouse",
                                                               				origo:"enterpoint",
                                                               				speed:400,
                                                               				speedbg:0,
                                                               				speedls:0,
                                                               				levels:[-2,-1,0,1,2,3,4,5,6,7,8,9,10,11,12,55],
                                                               				disable_onmobile:"on"
                                                               			},
                                                               			shadow:0,
                                                               			spinner:"spinner0",
                                                               			stopLoop:"off",
                                                               			stopAfterLoops:-1,
                                                               			stopAtSlide:-1,
                                                               			shuffle:"off",
                                                               			autoHeight:"off",
                                                               			disableProgressBar:"on",
                                                               			hideThumbsOnMobile:"off",
                                                               			hideSliderAtLimit:0,
                                                               			hideCaptionAtLimit:0,
                                                               			hideAllCaptionAtLilmit:0,
                                                               			debugMode:false,
                                                               			fallbacks: {
                                                               				simplifyAll:"off",
                                                               				nextSlideOnWindowFocus:"off",
                                                               				disableFocusListener:false,
                                                               			}
                                                               		});
                                                               	}; /* END OF revapi call */
                                                               	
                                                                }; /* END OF ON LOAD FUNCTION */
                                                               }()); /* END OF WRAPPING FUNCTION */
                                                            </script>
                                                         </div>
                                                         <!-- END REVOLUTION SLIDER -->		
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </section>
                                 <?php } ?>
                                 <?php if (false) { ?>
                                 <section class="elementor-element elementor-element-0be9206 animated-fast elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible elementor-section elementor-top-section" data-id="0be9206" data-element_type="section" data-settings="{&quot;animation&quot;:&quot;opal-move-up&quot;}">
                                    <div class="elementor-container elementor-column-gap-no">
                                       <div class="elementor-row">
                                          <div class="elementor-element elementor-element-0f35fb8 animated-fast elementor-invisible elementor-column elementor-col-100 elementor-top-column" data-id="0f35fb8" data-element_type="column" data-settings="{&quot;animation&quot;:&quot;opal-move-up&quot;}">
                                             <div class="elementor-column-wrap  elementor-element-populated">
                                                <div class="elementor-widget-wrap">
                                                   <div class="elementor-element elementor-element-b6032c9 elementor-widget elementor-widget-heading" data-id="b6032c9" data-element_type="widget" data-widget_type="heading.default">
                                                      <div class="elementor-widget-container">
                                                         <h2 class="elementor-heading-title elementor-size-default"><?php echo gtext("Il nostro blog");?></h2>
                                                      </div>
                                                   </div>
                                                   <div class="elementor-element elementor-element-99a278f elementor-widget elementor-widget-text-editor" data-id="99a278f" data-element_type="widget" data-widget_type="text-editor.default">
                                                      <div class="elementor-widget-container">
                                                         <div class="elementor-text-editor elementor-clearfix">
                                                            <p><?php echo gtext("Testo da modificare");?></p>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="elementor-element elementor-element-07ad555 elementor-widget elementor-widget-opal-post-grid" data-id="07ad555" data-element_type="widget" data-widget_type="opal-post-grid.default">
                                                      <div class="elementor-widget-container">
                                                         <div class="elementor-post-wrapper">
                                                            <div class="row" data-elementor-columns="3">
																<?php foreach ($ultimiArticoli as $p) {
																	$urlAlias = getUrlAlias($p["pages"]["id_page"]);
																	$urlAliasCategoria = getCategoryUrlAlias($p["pages"]["id_c"]);
																?>
																<div class="column-item post-style-1">
																	<div class="post-inner">
																		<div class="post-thumbnail">
																			<a href="<?php echo $this->baseUrl."/".$urlAlias;?>">
																			<img width="600" height="390" src="<?php echo $this->baseUrlSrc."/thumb/blog/".$p["pages"]["immagine"];?>" class="attachment-auros-featured-image-large size-auros-featured-image-large wp-post-image" alt="" />                </a>
																		</div>
																		
																		<div class="post-content">
																			<div class="entry-header">
																			<span class="entry-category "><a href="<?php echo $this->baseUrl."/".$urlAliasCategoria;?>" rel="category tag"><?php echo cfield($p, "title");?></a></span>
																			<span class="post-date"><?php echo traduci(date("d F Y", strtotime($p["pages"]["data_news"])));?></span>
																			</div>
																			<h3 class="entry-title"><a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo field($p, "title");?></a></h3>
																			<div class="entry-excerpt"><?php echo tagliaStringa(htmlentitydecode(field($p, "description")),100);?></div>
																			<div class="link-more"><a href="<?php echo $this->baseUrl."/".$urlAlias;?>"><?php echo gtext("+ Leggi tutto");?></a></div>
																		</div>
																	</div>
																</div>
																<?php } ?>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </section>
                                 <?php } ?>
                                 
                                 
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- .entry-content -->
                  </div>
                  <!-- .wrap -->
               </div>
               <!-- .panel-content -->
            </article>
            <!-- #post-## -->
         </main>
         <!-- #main -->
      </div>
      <!-- #primary -->
   </div>
   <!-- #content -->
</div>
<!-- .site-content-contain -->
