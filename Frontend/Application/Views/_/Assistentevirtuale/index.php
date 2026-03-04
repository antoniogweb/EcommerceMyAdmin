<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="assistente_virtuale_layout uk-padding-small uk-flex uk-flex-column uk-height-viewport uk-background-default uk-card uk-card-default uk-card-body uk-padding-small@s uk-padding">
	<div class="assistente_virtuale_messages uk-flex-1 uk-overflow-auto uk-margin-small-bottom">
		<div class="uk-grid-small chat_messages" uk-grid>
			<?php echo include(tpf("Assistentevirtuale/messaggi.php"));?>
		</div>
	</div>
	
	<div class="assistente_virtuale_composer uk-card uk-card-default uk-card-body uk-padding-small uk-margin-remove-top">
		<div class="uk-flex uk-flex-middle uk-grid-small" uk-grid>
			<div class="uk-width-expand">
				<input class="request_message uk-input" type="text" placeholder="<?php echo gtext("Scrivi un messaggio...");?>">
			</div>
			<div class="uk-width-auto">
				<button class="send_request_to_va uk-button uk-button-primary uk-flex uk-flex-center uk-flex-middle" type="button">
					<span class="send_request_to_va_text"><?php echo gtext("Invia");?></span>
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
