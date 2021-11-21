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
				width: 320px;
				z-index:1;
			}
			
			#left-col {
				margin-right: 320px;
			}
			
			iframe
			{
				display: block;
				width:100%;
				height:100%;
			}
			
			.uk-text-small
			{
				font-size:12px !important;
				line-height:1em !important;
			}
			
		</style>
		<?php include(tpf("/Elementi/header_css_cms.php"));?>
		
		<?php include(tpf("/Elementi/header_js_cms.php"));?>
   </head>
   <body class="uk-height-1-1">
		<div id="left-col" class="uk-height-1-1">
			<iframe id="iframe_webpage" class="uk-height-1-1" src="<?php echo $currentUrl;?>"></iframe>
		</div>
		<aside id="right-col" class="">
			<div class="uk-padding-small">
				<a href="<?php echo $currentUrl;?>" class="uk-button uk-button-default uk-width-1-1"><?php echo gtext("Esci modalità edit")?> <span uk-icon="sign-out"></span></a>
				<ul uk-accordion>
					<li v-show="mostraFasce" class="uk-open">
						<a class="uk-accordion-title" href="#"><?php echo gtext("Gestione fasce sito");?></a>
						<div class="uk-accordion-content">
							<table class="uk-table uk-table-divider uk-table-striped uk-table-small">
								<tbody class="sortable" uk-sortable="handle: .uk-sortable-handle">
									<tr v-for="f in fasce" :key="f.contenuti.id_cont">
										<td><span class="uk-sortable-handle" uk-icon="table"></span></td>
										<td class="fascia uk-padding-remove-left uk-padding-remove-right" v-bind:data-id="f.contenuti.id_cont">
											<div>
												<span class="uk-text-meta uk-text-small">{{ f.tipi_contenuto.titolo }}</span>
											</div>
											<div>
												<span class="uk-text-small">{{ f.contenuti.titolo }}</span>
											</div>
										</td>
										<td><a href="" @click.prevent="eliminaFascia(f.contenuti.id_cont)"><span class="uk-text-danger" uk-icon="trash"></span></a></td>
									</tr>
								</tbody>
							</table>
							<div v-if="fasce.length == 0" class="uk-margin uk-alert uk-alert-primary">
								<?php echo gtext("Nessuna fascia presente");?>
							</div>
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
				</ul>
			</div>
		</aside>
   		<?php include(tpf("/Elementi/footer_js_cms.php"));?>
   		
   		<?php
   		$urlFasce = ContenutiModel::$tipoElementoCorrente == "pagine" ? "pagine/contenuti/" : "categorie/contenuti/";
   		$urlFasce = $this->baseUrlSrc."/admin/".$urlFasce.ContenutiModel::$idElementoCorrente."?esporta_json";
   		?>
   		
   		<script src="<?php echo $this->baseUrlSrc."/admin/Public/Js/vue.min.js";?>"></script>
   		<script type="application/javascript">
		
   		var urlGetTipiFasce = "<?php echo $this->baseUrlSrc."/admin/tipicontenuto/main?tipo=FASCIA&esporta_json";?>";
   		
   		var app = new Vue({
			el: '#right-col',
			data: {
				urlGetFasce: "<?php echo $urlFasce;?>",
				urlPostFasce: "",
				inizializzato: false,
				tipoElemento: "",
				idElemento: 0,
				mostraFasce: true,
				idTipoFascia: 0,
				titoloNuovaFascia: "",
				aggiungi: true,
				confermataAggiunta: false,
				fasce: [
					{
						contenuti : {
							id_cont: 0,
							titolo: "",
						},
						tipi_contenuto: {
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
							url: that.urlPostFasce,
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
					this.titoloNuovaFascia = "";
				},
				preparaAggiungi: function()
				{
					this.aggiungi = false;
				},
				eliminaFascia: function(id)
				{
					var that = this;
					
					$.ajaxQueue({
						url: that.urlGetFasce+"&id_cont="+id+"&delAction=Y",
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
						url: that.urlGetFasce,
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
// 							console.log(that.tipiFasce);
						}
					});
				},
				inizializza: function()
				{
					var that = this;
					
					$('#iframe_webpage').on("load", function() {
						var elementoFasce = $('#iframe_webpage').contents().find(".blocco_fasce_contenuto").length;
						
						if (elementoFasce == 0)
							that.mostraFasce = false;
						else
							that.mostraFasce = true;
						
						that.idElemento = $('#iframe_webpage').contents().find(".class_id_contenuto").text();
						that.tipoElemento = $('#iframe_webpage').contents().find(".class_tipo_elemento").text();
						
						if (that.idElemento != 0 && that.tipoElemento != "")
						{
// 								console.log(that.tipoElemento);
							
							that.urlGetFasce = baseUrlSrc + "/admin/" + that.tipoElemento + "/contenuti/" + that.idElemento + "?esporta_json";
							
							var queryStringIdElemento = (that.tipoElemento == "pagine") ? "id_page" : "id_c";
							
							that.urlPostFasce = baseUrlSrc + "/admin/contenuti/form/insert?" + queryStringIdElemento + "=" + that.idElemento;
							
	// 						console.log(that.urlPostFasce);
							
							that.geFasce();
							
							if (!that.inizializzato)
								that.geTipiFasce();
							
							that.inizializzato = true;
						}
					});
				}
			},
			beforeMount(){
				this.inizializza();
			}
		});
   		
//    		app.inizializza();
   		
   		function aggiornaIframe()
   		{
			document.getElementById("iframe_webpage").contentDocument.location.reload(true);
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
		<div style="display:none;" class="class_request_uri"><?php echo sanitizeAll($_SERVER['REQUEST_URI']);?></div>
   </body>
</html>
