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
				width: 340px;
				z-index:1;
			}
			
			#left-col {
				margin-right: 340px;
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
					<li v-if="abilitaGestioneTemi && tendinaTemi.length > 0" class="">
						<a class="uk-accordion-title" href="#"><?php echo gtext("Gestione tema");?></a>
						<div class="uk-accordion-content">
							<div class="uk-text-meta"><?php echo gtext("Tema corrente");?></div>
							<select v-model="temaSelezionato" class="uk-margin-remove uk-select uk-margin-small" v-on:change="cambiaTema()">
								<option  v-for="(tema, index) in tendinaTemi" v-bind:value="tema.nome">{{tema.nome}}</option>
							</select>
						</div>
					</li>
					<li v-if="abilitaGestioneVarianti && varianti.length > 0" class="">
						<a class="uk-accordion-title" href="#"><?php echo gtext("Varianti pagina");?></a>
						<div class="uk-accordion-content">
							<variante-item v-for="variante in varianti" v-bind:variante="variante"></variante-item>
						</div>
					</li>
					<li v-show="mostraFasce" class="uk-open">
						<a class="uk-accordion-title" href="#"><?php echo gtext("Fasce pagina");?></a>
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
										<td class="uk-padding-remove-left uk-padding-remove-right"><a title="<?php echo gtext("Modifica");?>" href="#" @click.prevent="modificaFascia(f.contenuti.id_cont)" class="iframe"><span class="" uk-icon="pencil"></span></a></td>
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
		
		<!-- This is the modal -->
		<div id="modale-fascia" class="" uk-modal>
			<div class="uk-height-1-1 uk-modal-dialog uk-modal-body uk-width-auto">
				<button class="uk-modal-close-full uk-close large" type="button" uk-close></button>
				<iframe class="" id="" src=""></iframe>
			</div>
		</div>

   		<?php include(tpf("/Elementi/footer_js_cms.php"));?>
   		
   		<?php
   		$urlFasce = ContenutiModel::$tipoElementoCorrente == "pagine" ? "pagine/contenuti/" : "categorie/contenuti/";
   		$urlFasce = $this->baseUrlSrc."/admin/".$urlFasce.ContenutiModel::$idElementoCorrente."?esporta_json";
   		?>
   		
   		<script src="<?php echo $this->baseUrlSrc."/admin/Public/Js/vue.min.js";?>"></script>
   		<script type="application/javascript">
		
   		var urlGetTipiFasce = "<?php echo $this->baseUrlSrc."/admin/tipicontenuto/main?tipo=FASCIA&esporta_json";?>";
   		var urlPostElementi = "<?php echo $this->baseUrlSrc."/admin/elementitema/form/update/";?>";
   		var tendinaTemi = <?php echo json_encode(Tema::getElencoTemi());?>;
   		
//    		console.log(tendinaTemi);
   		
   		Vue.component('variante-item', {
			props: ['variante'],
			data: function () {
				return {
					nomeFile: "",
				}
			},
			methods: {
				sendData: function(value)
				{
					var that = this;
					var url = urlPostElementi + this.variante.id_elemento_tema;
					
					$.ajaxQueue({
						url: url,
						async: true,
						cache:false,
						dataType: "html",
						method: "POST",
						data: {
							titolo: this.variante.titolo,
							nome_file: this.nomeFile,
							id_elemento_tema: this.variante.id_elemento_tema,
							updateAction: "Salva",
						},
						success: function(content){
							aggiornaIframe();
						}
					});
				}
			},
			mounted(){
// 				console.log(this.variante.codice);
				this.nomeFile = this.variante.nome_file;
			},
			updated(){
// 				console.log(this.variante.codice);
				this.nomeFile = this.variante.nome_file;
			},
			template: `<div>
							<div class='uk-text-meta'>{{variante.titolo}}</div>
							<select class="uk-select uk-margin-small" v-model="nomeFile" v-on:change="sendData()">
								<option v-for="(opzione, index) in variante.opzioni" v-bind:value="opzione.k" v-bind:key="variante.nome_file">{{opzione.v}}</option>
							</select>
						</div>
					`
		});
   		
   		var app = new Vue({
			el: '#right-col',
			data: {
				baseUrlSrc: baseUrlSrc,
				temaSelezionato: "",
				tendinaTemi: tendinaTemi,
				varianti: [],
				abilitaGestioneVarianti : <?php echo v("attiva_elementi_tema") ? "true" : "false"?>,
				abilitaGestioneTemi : <?php echo v("permetti_cambio_tema") ? "true" : "false"?>,
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
				cambiaTema: function()
				{
					var that = this;
					
					$.ajaxQueue({
						url: baseUrlSrc + "/admin/impostazioni/attivatema/" + this.temaSelezionato,
						async: true,
						cache:false,
						dataType: "html",
						success: function(content){
							
							aggiornaIframe();
						}
					});
				},
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
				modificaFascia: function(id)
				{
					var url = this.baseUrlSrc + "/admin/contenuti/form/update/" + id + "?partial=Y"
					$("#modale-fascia").find("iframe").attr("src", url)
					UIkit.modal("#modale-fascia",{}).show();
// 					console.log("#" + id + " .class_edit_fascia");
// 					console.log($('#iframe_webpage').contents().find("#" + id).length);
// 					$('#iframe_webpage').contents().find("#" + id + " .class_edit_fascia").trigger("click");
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
						console.log(elementoFasce);
						if (elementoFasce == 0)
							that.mostraFasce = false;
						else
							that.mostraFasce = true;
						
						that.idElemento = $('#iframe_webpage').contents().find(".class_id_contenuto").text();
						that.tipoElemento = $('#iframe_webpage').contents().find(".class_tipo_elemento").text();
						
						if (that.abilitaGestioneVarianti)
							that.varianti = JSON.parse($('#iframe_webpage').contents().find(".class_json_varianti").text());
						
						that.temaSelezionato = $('#iframe_webpage').contents().find(".class_tema_default").text();
// 						console.log(that.varianti);
						
// 						that.$forceUpdate();
						
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
			
			UIkit.util.on('#modale-fascia', 'hide', function () {
				aggiornaIframe();
			});
		});
		</script>
   </body>
</html>
