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
		<aside id="right-col" class="">
			<div class="uk-padding-small">
				<ul uk-accordion>
					<?php if (count(ContenutiModel::$fascePagina) > 0) { ?>
					<li class="uk-open">
						<a class="uk-accordion-title" href="#"><?php echo gtext("Gestione fasce sito");?></a>
						<div class="uk-accordion-content">
							<table class="uk-table uk-table-divider uk-table-striped uk-table-small">
								<tbody class="sortable" uk-sortable="handle: .uk-sortable-handle">
									<tr v-for="f in fasce">
										<td><span class="uk-sortable-handle" uk-icon="table"></span></td>
										<td class="fascia" v-bind:data-id="f.contenuti.id_cont"><span class="uk-text-small">{{ f.contenuti.titolo }}</span></td>
										<td><a href="" @click.prevent="eliminaFascia(f.contenuti.id_cont)"><span class="uk-text-danger" uk-icon="trash"></span></a></td>
									</tr>
								</tbody>
							</table>
							<a v-if="aggiungi" @click.prevent="preparaAggiungi()" href="" class="uk-button uk-button-secondary">Aggiungi fascia</a>
							<div v-if="!aggiungi">
								<input class="uk-input" placeholder="Titolo fascia"/>
							</div>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</aside>
   		<?php include(tpf("/Elementi/footer_js_cms.php"));?>
   		
   		<?php
   		$urlFasce = ContenutiModel::$tipoElementoCorrente == "pagine" ? "pagine/contenuti/" : "categorie/contenuti/";
   		$urlFasce = $this->baseUrlSrc."/admin/".$urlFasce.ContenutiModel::$idElementoCorrente."?esporta_json";
   		?>
   		
   		<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
   		<script type="application/javascript">
   		
   		var urlGetFasce = "<?php echo $urlFasce;?>";
   		
   		var app = new Vue({
			el: '#right-col',
			data: {
				aggiungi: true,
				fasce: [
					{
						contenuti : {
							id_cont: 0,
							titolo: "",
						}
					}
				],
			},
			methods:{
				preparaAggiungi: function()
				{
					this.aggiungi = false;
				},
				eliminaFascia: function(id)
				{
					var that = this;
					
					$.ajaxQueue({
						url: urlGetFasce+"&id_cont="+id+"&delAction=Y",
						async: true,
						cache:false,
						dataType: "json",
						success: function(content){
							
							that.geFasce();
							aggiornaIframe();
						}
					});
				},
				geFasce: function() {
					var that = this;
					
					$.ajaxQueue({
						url: urlGetFasce,
						async: true,
						cache:false,
						dataType: "json",
						success: function(content){
							
							that.fasce = content;
// 							console.log(that.fasce);
						}
					});
				}
			},
			beforeMount(){
				this.geFasce()
			}
		});

   		
   		
   		function aggiornaIframe()
   		{
			document.getElementById("iframe").contentDocument.location.reload(true);
   		}
//    		
//    		function aggiornaFasce()
//    		{
// 			
//    		}
//    		
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
// 
   		$(document).ready(function() {
			UIkit.util.on('.sortable', 'moved', function (item) {
				aggiornaOrdinamento();
			});
		});
		</script>
   </body>
</html>
