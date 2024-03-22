<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class='row'>
	<form id-ticket="<?php echo $id;?>" id="ancora_form" class="formClass no-check-modifiche" method="POST" action="<?php echo $this->baseUrl."/".$this->controller."/form/$type/$id".$this->viewStatus;?>#ancora_form" enctype="multipart/form-data">
		<div class='col-md-4'>
			<h4 class="text-bold" style="padding-bottom:10px;"><?php echo gtext("Dati generali ticket");?></h4>
			
			<?php if (isset($form["id_user"])) { ?>
				<?php echo $form["id_user"];?>
			<?php } ?>
			<?php echo $form["oggetto"];?>
			<?php echo $form["descrizione"];?>
			<?php echo $form["id_ticket_tipologia"];?>
			<?php echo $form["id_o"] ?? "";?>
			<?php echo $form["id_lista_regalo"] ?? "";?>
			
			<?php if ($mostra_tendina_prodotti) { ?>
			<label class="uk-form-label"><?php echo gtext("Seleziona il prodotto");?> *</label>
			<div class="uk-form-controls"><?php echo Html_Form::select("id_page",0,$prodotti,"form-control class_id_page",null,"yes");?></div>
			<br />
			<label class="uk-form-label"><?php echo gtext("Scrivi il numero seriale del prodotto");?></label>
			<div class="uk-form-controls"><?php echo Html_Form::input("numero_seriale","","form-control class_numero_seriale",null,"placeholder='".gtext("Numero seriale", false)."'");?></div>
			
<!-- 			<br /> -->
			
			<hr />
			<?php } ?>
			
			<?php if (count($prodottiInseriti) > 0) { ?>
			<div class="uk-overflow-auto">
				<table class="table table-striped" cellspacing="0">
					<?php foreach ($prodottiInseriti as $p) { ?>
					<tr class="ordini_table_row uk-text-small">
						<td><img style="max-width:60px;" src="<?php echo $this->baseUrlSrc."/thumb/immagineinlistaprodotti/".$p["pages"]["id_page"]."/".$p["pages"]["immagine"];?>" alt="<?php echo altUrlencode(field($p, "title"));?>"></td>
						<td><?php echo field($p, "title");?><br /><?php echo gtext("N.Seriale");?>: <b><?php echo $p["ticket_pages"]["numero_seriale"] ? $p["ticket_pages"]["numero_seriale"] : "--" ;?></b></td>
						<td class="text-right">
							<a id-page="<?php echo (int)$p["pages"]["id_page"];?>" class="text_16 elimina_dal_tiket make_spinner text text-danger" title="<?php echo gtext("Elimina il prodotto dal ticket",false);?>" href="<?php echo $this->baseUrl."/ticket/rimuoviprodotto/$id/".$ticket["ticket_uid"];?>"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
			<?php } ?>
			
			<div class="submit_entry">
				<span class="submit_entry_Salva pull-right">
					<button id="<?php echo $type;?>Action" class="btn btn-success make_spinner" name="<?php echo $type;?>Action" type="submit"><i class="fa fa-save"></i> <?php echo gtext("Salva e invia il ticket");?></button>
					<input type="hidden" value="Salva" name="<?php echo $type;?>Action">
				</span>
				<?php if ($mostra_tendina_prodotti) { ?>
				<a href="<?php echo $this->baseUrl."/ticket/aggiungiprodotto/$id/".$ticket["ticket_uid"];?>" title="<?php echo gtext("Aggiungi il prodotto al ticket")?>" class="btn btn-primary aggiungi_al_ticket"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi prodotto al ticket");?></a>
				<?php } ?>
				
			</div>
		</div>
		<div class='col-md-8'>
			<?php if ($recordTicket["ticket"]["stato"] != "B") { ?>
			<h4 class="text-bold" style="padding-bottom:10px;"><?php echo gtext("Messaggi");?></h4>
			
			<?php foreach ($messaggi as $m) {
				$scrittoDa = $m["ticket_messaggi"]["id_user"] ? $nominativoCliente : $m["adminusers"]["username"];
			?>
			<div class="post <?php if (!$m["ticket_messaggi"]["id_user"]) { ?>text-right"<?php } ?>">
				<div class="user-block">
					<span class="username" style="margin-left:0px !important;">
						<b style="color:#3c8dbc;"><?php echo $scrittoDa;?></b>
					</span>
					<span class="description" style="margin-left:0px !important;"><?php echo gtext("Scritto in data");?> <?php echo date("d-m-Y H:i", strtotime($m["ticket_messaggi"]["data_creazione"]));?></span>
					</div>

					<div style="width:50%;" <?php if (!$m["ticket_messaggi"]["id_user"]) { ?>class="well pull-right"<?php } ?>>
						<?php if (!$m["ticket_messaggi"]["id_user"]) { ?>
						<?php echo htmlentitydecode($m["ticket_messaggi"]["descrizione"]);?>
						<?php } else { ?>
						<?php echo $m["ticket_messaggi"]["descrizione"];?>
						<?php } ?>
					</div>
					
					<?php if ($m["ticket_messaggi"]["filename"]) { ?>
						<div style="clear:both;"></div>
						<?php if (TicketfileModel::fileEsistente($m["ticket_messaggi"]["filename"])) { ?>
							<?php if ($m["ticket_messaggi"]["id_user"]) { ?><br /><?php } ?>
							<div <?php if (!$m["ticket_messaggi"]["id_user"]) { ?>class="pull-right"<?php } ?>>
								<?php if ($m["ticket_messaggi"]["tipo"] == "IMMAGINE") { ?>
								<a target="_blank" href="<?php echo $this->baseUrlSrc."/thumb/immagineticket/".$m["ticket_messaggi"]["filename"];?>"><img style="max-width:100px;" src="<?php echo $this->baseUrlSrc."/thumb/immagineticket/".$m["ticket_messaggi"]["filename"];?>" /></a>
								<?php } else {
									$daElaborare = TicketfileModel::daElaborare($m["ticket_messaggi"]["filename"]);
								?>
									<?php echo gtext("file allegato");?>:
									<?php if (!$daElaborare) { ?>
									<a target="_blank" href="<?php echo Domain::$publicUrl."/ticket/scarica/".$m["ticket_messaggi"]["filename"];?>">
									<?php } ?>
										<span class="uk-text-small"><?php echo $m["ticket_messaggi"]["clean_filename"];?></span>
									<?php if (!$daElaborare) { ?>
									</a>
									<?php } else { ?>
									<span class="uk-text-italic uk-text-small">(<?php echo gtext("in elaborazione");?> <span uk-icon="icon: clock;ratio: 0.7"></span>)</span>
									<?php } ?>
								<?php } ?>
							</div>
						<?php } else { ?>
						<?php echo gtext("File video eliminato a causa di problemi nella compressione.")?>
						<?php } ?>
					<?php } ?>
					
					<div style="clear:both;"></div>
				</div>
			<?php } ?>
			<hr />
			<a href="<?php echo $this->baseUrl."/ticketmessaggi/form/insert?cl_on_sv=Y&partial=Y&nobuttons=Y&id_ticket=".(int)$id;?>" class="iframe btn btn-primary pull-right"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi messaggio");?></a>
			<?php } ?>
		</div>
	</form>
</div>

<script type="text/javascript">

function salvaBozza()
{
	var idTicket = $(".formClass").attr("id-ticket");
	
	var url = baseUrl + "/ticket/salvabozza/" + idTicket;
	
	$.ajaxQueue({
		url: url,
		async: true,
		cache:false,
		dataType: "html",
		type: "POST",
		data: $('.formClass').serialize(),
		success: function(content){

		}
	});
}

$(document).ready(function(){
	
	$('[name="id_user"]').on('select2:select', function (e) {
		reloadPage();
	});
		
	$( "body" ).on( "change", "[name='id_ticket_tipologia'],[name='id_o'],[name='id_lista_regalo']", function(e) {
		reloadPage();
	});
	
	$( "body" ).on( "change", "[name='id_ticket_tipologia'],[name='id_o'],[name='id_lista_regalo'],[name='oggetto'],[name='descrizione']", function(e) {
		salvaBozza();
	});
	
	$( "body" ).on( "click", ".aggiungi_al_ticket", function(e) {
		
		e.preventDefault();
		
		var id_page = $("[name='id_page']").val();
		var numero_seriale = $("[name='numero_seriale']").val();
		
		var url = $(this).attr("href");
		
		if (id_page != 0)
		{
			$.ajaxQueue({
				url: url,
				async: true,
				cache:false,
				dataType: "html",
				type: "POST",
				data: {
					id_page: id_page,
					numero_seriale: numero_seriale
				},
				success: function(content){
					
					reloadPage();
					
				}
			});
		}
		else
		{
			alert("Si prega di selezionare un prodotto");
		}
	});
	
	$( "body" ).on( "click", ".elimina_dal_tiket", function(e) {
		
		e.preventDefault();
		
		var url = $(this).attr("href");
		var id_page = $(this).attr("id-page");
		
		$.ajaxQueue({
			url: url,
			async: true,
			cache:false,
			dataType: "html",
			type: "POST",
			data: {
				id_page: id_page
			},
			success: function(content){
				
				reloadPage();
				
			}
		});
		
	});
});
</script>
