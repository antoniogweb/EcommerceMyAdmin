<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-navbar-dropdown uk-padding-small uk-margin-remove" uk-drop="mode: click;cls-drop: uk-navbar-dropdown;boundary: .tm-navbar-container;boundary-align: true;pos: bottom-justify;flip: x">
	<div class="uk-container">
		<div class="uk-grid-small uk-flex-middle" uk-grid>
			<div class="uk-width-expand">
				<form class="uk-search uk-search-navbar uk-width-1-1" action="<?php echo $this->baseUrl."/risultati-ricerca";?>" method="get">
					<input autocomplete="off" name="s" class="uk-search-input" type="search" placeholder="<?php echo gtext("Cerca un prodotto..", false)?>" autofocus/>
				</form>
			</div>
			<div class="uk-width-auto"><a class="uk-navbar-dropdown-close" href="#" uk-close></a></div>
		</div>
	</div>
</div>
