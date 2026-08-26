<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="assistente_virtuale_layout uk-flex uk-flex-column uk-height-viewport uk-background-default">
	<div class="assistente_virtuale_messages uk-flex-1 uk-overflow-auto">
		<div class="chat_welcome_message">
			<div class="chat_message_row chat_message_row_assistant">
				<div class="chat_message_bubble chat_message_bubble_assistant">
					<?php echo gtextPlain("Sono il tuo assistente virtuale: puoi  cercare un prodotto, chiedere informazioni sulle spedizioni, sugli orari, contatti, liste nascita, resi e altro supporto in qualsiasi momento.");?>
				</div>
			</div>
		</div>
		<div class="chat_messages">
			<?php include(tpf("Assistentevirtuale/messaggi.php"));?>
		</div>
		<div class="assistente_virtuale_status uk-hidden uk-text-small uk-text-muted uk-padding-small"></div>
	</div>
	
	<?php $ticketCreato = !empty($chat) && (int)$chat["ticket_creato"]; ?>
	<div class="assistente_virtuale_composer uk-margin-remove-top">
		<div class="assistente_virtuale_composer_new_chat" style="<?php echo $ticketCreato ? "" : "display:none;";?>">
			<div class="uk-flex uk-flex-center">
				<a class="uk-button uk-button-primary" href="<?php echo $this->baseUrl."/virtual-assistant/new-chat/";?>">
					<?php echo gtextPlain("Nuova chat");?>
				</a>
			</div>
		</div>
		<div class="assistente_virtuale_composer_message" style="<?php echo $ticketCreato ? "display:none;" : "";?>">
			<div class="uk-flex uk-flex-middle uk-grid-small" uk-grid>
				<div class="uk-width-expand">
					<input class="request_message uk-input" type="text" placeholder="<?php echo gtextAttr("Scrivi un messaggio...");?>">
				</div>
				<div class="uk-width-auto" style="margin-top:0px !important;">
					<button class="send_request_to_va uk-button uk-button-primary uk-flex uk-flex-center uk-flex-middle" type="button">
						<span class="send_request_to_va_text"><?php echo gtextPlain("Invia");?></span>
						<span class="send_request_to_va_loader uk-hidden" aria-hidden="true">
							<span></span>
							<span></span>
							<span></span>
						</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
