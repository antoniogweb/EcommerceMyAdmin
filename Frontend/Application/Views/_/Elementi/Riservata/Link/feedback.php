<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php if (v("abilita_feedback") && v("feedback_visualizza_in_area_riservata")) {
	$user_feedback = FeedbackModel::get(0,0);
	if (count($user_feedback) > 0) { ?>
	<li class="<?php if ($attiva == "feedback") { ?>uk-active<?php } ?>">
		<a href="<?php echo $this->baseUrl."/riservata/feedback";?>" title="<?php echo gtext("Le mie valutazioni", false);?>"><?php echo gtext("Le mie valutazioni");?></a>
	</li>
	<?php } ?>
<?php } ?>
