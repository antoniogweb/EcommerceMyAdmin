<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="assistente_virtuale_widget" data-chat-url="<?php echo $this->baseUrl.'/virtual-assistant/';?>">
	<?php if ($this->controller != "assistentevirtuale") { ?>
	<button
		class="assistente_virtuale_widget_toggle"
		type="button"
		aria-controls="assistente-virtuale-panel"
		aria-expanded="false"
		aria-label="<?php echo gtext("Apri la chat con l'assistente virtuale");?>">
		<span class="assistente_virtuale_widget_toggle_icon" aria-hidden="true">
			<svg viewBox="0 0 24 24" role="presentation" focusable="false">
				<path d="M12 3C6.48 3 2 6.94 2 11.8c0 2.67 1.35 5.07 3.5 6.68V22l3.24-1.79c1.01.27 2.1.41 3.26.41 5.52 0 10-3.94 10-8.82S17.52 3 12 3Zm-3.5 10.1a1.1 1.1 0 1 1 0-2.2 1.1 1.1 0 0 1 0 2.2Zm3.5 0a1.1 1.1 0 1 1 0-2.2 1.1 1.1 0 0 1 0 2.2Zm3.5 0a1.1 1.1 0 1 1 0-2.2 1.1 1.1 0 0 1 0 2.2Z"/>
			</svg>
		</span>
		<span class="assistente_virtuale_widget_toggle_text"><?php echo gtext('ChatBot');?></span>
	</button>
	<?php } ?>
	
	<div class="assistente_virtuale_widget_overlay"></div>

	<section
		id="assistente-virtuale-panel"
		class="assistente_virtuale_widget_panel"
		aria-hidden="true">
		<header class="assistente_virtuale_widget_header">
			<div class="assistente_virtuale_widget_header_text">
				<strong><?php echo gtext('Assistente virtuale');?></strong>
				<span><?php echo gtext('Risposte immediate su prodotti, ordini e supporto');?></span>
			</div>
			<button
				class="assistente_virtuale_widget_close"
				type="button"
				aria-label="<?php echo gtext('Chiudi la chat');?>">
				<span aria-hidden="true">&times;</span>
			</button>
		</header>

		<div class="assistente_virtuale_widget_body">
			<iframe
				class="assistente_virtuale_widget_iframe"
				title="<?php echo gtext('Chat assistente virtuale');?>"
				loading="lazy"
				allow="clipboard-write"></iframe>
		</div>
	</section>
</div>
