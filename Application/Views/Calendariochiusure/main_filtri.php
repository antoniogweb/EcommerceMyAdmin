<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<form class="form-inline" role="form" action='<?php echo $this->baseUrl."/".$this->applicationUrl.$this->controller."/main".$this->viewStatus;?>' method='POST' enctype="multipart/form-data">
	<div class="input-group date">
		<?php echo Html_Form::input("dal","","form-control data_field",null,"placeholder='".gtext("Dal ..")."' autocomplete='off'");?>
	<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>
	
	<div class="input-group date">
		<?php echo Html_Form::input("al","","form-control data_field",null,"placeholder='".gtext("Al ..")."' autocomplete='off'");?>
	<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>
	
	<button class="submit_file btn btn-primary btn-sm make_spinner" type="submit" name="aggiungi" value="<?php echo gtext("Aggiungi date");?>"><i class="fa fa-plus"></i> <?php echo gtext("Aggiungi date");?></button>
	<input type="hidden" name="aggiungi" value="Aggiungi" />
</form>
<br />