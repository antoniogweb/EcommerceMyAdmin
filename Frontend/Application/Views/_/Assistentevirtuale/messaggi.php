<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php foreach ($messaggi as $m) {
	$isUser = $m["ruolo"] == "user";
?>
	<div class="chat_message_row <?php echo $isUser ? "chat_message_row_user" : "chat_message_row_assistant";?>">
		<div class="chat_message_bubble <?php echo $isUser ? "chat_message_bubble_user" : "chat_message_bubble_assistant";?>">
			<?php if ($isUser) { ?>
				<?php echo nl2br($m["messaggio"]);?>
			<?php } else { ?>
				<?php echo htmlentitydecode(attivaModuli($m["messaggio"]));?>
			<?php } ?>
		</div>
	</div>
<?php } ?>
