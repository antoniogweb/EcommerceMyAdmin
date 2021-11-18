<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="<?php echo Params::$lang;?>" class="uk-height-1-1" uk-height-viewport>
   <head>
		<style>
			#right-col {
				position: fixed;
				right: 0;
				top:0;
				bottom:0;
				overflow-x: hidden;
				overflow-y: auto;
/* 				background-color: #222; */
				width: 300px;
				z-index:1;
			}
			
			#left-col {
				margin-right: 300px;
			}
			
			iframe
			{
				display: block;
				width:100%;
				height:100%;
			}
		</style>
		<?php include(tpf("/Elementi/header_css_cms.php"));?>
		
		<?php include(tpf("/Elementi/header_js_cms.php"));?>
   </head>
   <body class="uk-height-1-1">
		<div id="left-col" class="uk-height-1-1">
<!-- 			<div class="uk-cover-container uk-height-1-1"> -->
				<iframe id="iframe" class="uk-height-1-1" src="<?php echo $currentUrl;?>"></iframe>
<!-- 			</div> -->
		</div>
		<aside id="right-col" class="uk-background-muted">
			<div class="uk-padding-small">
				<ul uk-accordion>
					<?php if (count(ContenutiModel::$fascePagina) > 0) { ?>
					<li class="uk-open">
						<a class="uk-accordion-title" href="#"><?php echo gtext("Gestione fasce sito");?></a>
						<div class="uk-accordion-content">
							<table class="uk-table uk-table-divider uk-table-striped uk-table-small">
								<tbody class="sortable" uk-sortable="handle: .uk-sortable-handle">
									<?php foreach (ContenutiModel::$fascePagina as $cont) { ?>
									<tr>
										<td class="fascia" data-id="<?php echo $cont["contenuti"]["id_cont"];?>"><?php echo contfield($cont,"titolo");?></td>
										<td><span class="uk-sortable-handle" uk-icon="table"></span></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
   		<?php include(tpf("/Elementi/footer_js_cms.php"));?>
   		
   		<script>
   		
   		function aggiornaIframe()
   		{
			document.getElementById("iframe").contentDocument.location.reload(true);
   		}
   		
   		function aggiornaOrdinamento()
		{
			var id_cont = "";
			var order = "";
			
			$(".fascia").each(function(){
			
				var id_cont = $(this).attr("data-id");
			
				order += id_cont + ",";
			
			});
			
			var post_data = "order="+order+"&ordinaPagine=Y";
			
			$.ajaxQueue({
				type: "POST",
				data: post_data,
				url: "<?php echo $this->baseUrlSrc.'/admin/pages/ordinacontenuti';?>",
				async: true,
				cache:false,
				success: function(html){
					aggiornaIframe();
				}
			});
		}

   		$(document).ready(function() {
			UIkit.util.on('.sortable', 'moved', function (item) {
				aggiornaOrdinamento();
			});
		});
		</script>
   </body>
</html>
