<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-dropdown" uk-dropdown="pos: bottom-right">
	<div>
		<div class="uk-flex uk-flex-center">
			<form class="uk-search uk-search-default" style="width:90%;" action="<?php echo $this->baseUrl."/risultati-ricerca";?>" method="get">
				<button class="uk-search-icon-flip" uk-search-icon></button>
				<input autocomplete="off" name="s" class="uk-input" type="search" placeholder="<?php echo gtext("Cerca..", false)?>" autofocus>
			</form>
		</div>   
	</div>
</div>
 
