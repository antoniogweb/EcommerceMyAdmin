<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;margin:0;">
	<tr>
		<td style="padding:0;">
			<h2 style="margin:0 0 14px;font-size:18px;line-height:24px;font-weight:bold;color:#111827;"><?php echo gtextPlain("Storico chat assistente virtuale");?></h2>
			<?php if (!empty($chat)) { ?>
				<p style="margin:0 0 18px;font-size:13px;line-height:18px;color:#52616f;">
					<?php echo gtextPlain("Chat");?> #<?php echo (int)$chat["id_ai_richiesta"];?>
					<?php if (isset($chat["data_creazione"]) && trim($chat["data_creazione"])) { ?>
						 - <?php echo $chat["data_creazione"];?>
					<?php } ?>
				</p>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td style="padding:0;">
			<?php if (!empty($messaggi)) { ?>
				<?php foreach ($messaggi as $m) {
					$isUser = $m["ruolo"] == "user";
					$label = $isUser ? gtext("Cliente") : gtext("Assistente virtuale");
					$background = $isUser ? "#e8f1ff" : "#f3f4f6";
					$border = $isUser ? "#bfd7ff" : "#d8dee4";
				?>
					<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;border-collapse:collapse;margin:0 0 14px;">
						<tr>
							<td style="padding:0;">
								<div style="font-size:12px;line-height:16px;font-weight:bold;color:#52616f;margin:0 0 4px;">
									<?php echo $label;?>
									<?php if (isset($m["data_creazione"]) && trim($m["data_creazione"])) { ?>
										<span style="font-weight:normal;color:#7b8794;"> - <?php echo sanitizeHtml($m["data_creazione"]);?></span>
									<?php } ?>
								</div>
								<div style="background:<?php echo $background;?>;border:1px solid <?php echo $border;?>;padding:12px 14px;font-size:14px;line-height:21px;color:#1f2933;">
									<?php if ($isUser) { ?>
										<?php echo nl2br($m["messaggio"]);?>
									<?php } else { ?>
										<?php echo htmlentitydecode(attivaModuli($m["messaggio"]));?>
									<?php } ?>
								</div>
							</td>
						</tr>
					</table>
				<?php } ?>
			<?php } else { ?>
				<p style="margin:0;font-size:14px;line-height:21px;color:#52616f;"><?php echo gtextPlain("Non ci sono messaggi in questa chat.");?></p>
			<?php } ?>
		</td>
	</tr>
</table>
