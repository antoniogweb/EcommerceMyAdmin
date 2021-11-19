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
			<iframe id="iframe" class="uk-height-1-1" src="<?php echo $currentUrl;?>"></iframe>
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
							<a v-if="aggiungi" @click.prevent="preparaAggiungi()" href="" class="uk-button uk-button-secondary uk-width-1-1"><span uk-icon="plus"></span> Nuova fascia</a>
							<div v-if="!aggiungi">
								<input v-bind:class="oggettoErroreTitolo" v-model="titoloNuovaFascia" class="uk-input" placeholder="Titolo fascia nuova fascia"/>
								<select v-bind:class="oggettoErroreIdTipo" v-model="idTipoFascia" class="uk-select uk-margin-small">
									<option  v-for="(tipoFascia, index) in tipiFasce" v-bind:value="tipoFascia.tipi_contenuto.id_tipo">{{tipoFascia.tipi_contenuto.titolo}}</option>
								</select>
								<a @click.prevent="confermaAggiungi()" href="" class="uk-button uk-button-secondary uk-width-1-1"><span uk-icon="check"></span> Aggiungi</a>
								<a @click.prevent="annullaAggiungi()" href="" class="uk-margin-small uk-button uk-button-default uk-width-1-1"><span uk-icon="arrow-left"></span> Annulla</a>
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
   		$queryStringIdElemento = ContenutiModel::$tipoElementoCorrente == "pagine" ? "id_page" : "id_c";
   		?>
   		
   		<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
   		<script type="application/javascript">
   		
   		var urlGetFasce = "<?php echo $urlFasce;?>";
   		var urlPostFasce = "<?php echo $this->baseUrlSrc."/admin/contenuti/form/insert?".$queryStringIdElemento."=".ContenutiModel::$idElementoCorrente;?>";
   		var urlGetTipiFasce = "<?php echo $this->baseUrlSrc."/admin/tipicontenuto/main?tipo=FASCIA&esporta_json";?>";
   		
   		var app = new Vue({
			el: '#right-col',
			data: {
				idTipoFascia: 0,
				titoloNuovaFascia: "",
				aggiungi: true,
				confermataAggiunta: false,
				fasce: [
					{
						contenuti : {
							id_cont: 0,
							titolo: "",
						}
					}
				],
				tipiFasce: [
					{
						tipi_contenuto:
						{
							id_tipo: 0,
							titolo: "",
						}
					}
				],
			},
			computed: {
				oggettoErroreTitolo: function () {
					return {
						'uk-form-danger': this.titoloNuovaFascia == "" && this.confermataAggiunta,
					}
				},
				oggettoErroreIdTipo: function () {
					return {
						'uk-form-danger': this.idTipoFascia == 0 && this.confermataAggiunta,
					}
				}
			},
			methods:{
				confermaAggiungi: function()
				{
					this.confermataAggiunta = true;
					
					if (this.titoloNuovaFascia != "" && this.idTipoFascia != 0)
					{
						var that = this;
						
						$.ajaxQueue({
							url: urlPostFasce,
							async: true,
							cache:false,
							dataType: "html",
							method: "POST",
							data: {
								titolo: this.titoloNuovaFascia,
								id_tipo: this.idTipoFascia,
								lingua: "tutte",
								attivo: "Y",
								insertAction: "Salva",
							},
							success: function(content){
								that.geFasce();
								aggiornaIframe();
								
								that.annullaAggiungi();
							}
						});
					}
				},
				annullaAggiungi: function()
				{
					this.aggiungi = true;
					this.confermataAggiunta = false;
				},
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
				},
				geTipiFasce: function() {
					var that = this;
					
					$.ajaxQueue({
						url: urlGetTipiFasce,
						async: true,
						cache:false,
						dataType: "json",
						success: function(content){
							
							that.tipiFasce = content;
							console.log(that.tipiFasce);
						}
					});
				}
			},
			beforeMount(){
				this.geFasce();
				this.geTipiFasce();
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
