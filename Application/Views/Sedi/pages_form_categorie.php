<?php if (!defined('EG')) die('Direct access not allowed!');

if (v("attiva_categorie_sedi"))
	include(ROOT."/Application/Views/pages_form_categorie.php");

?>
<div class="panel panel-info">
	<div class="panel-heading">
		<?php echo gtext("Social");?>
	</div>
	<div class="panel-body">
		<?php echo $form["link_pagina_facebook"];?>
		
		<?php echo $form["link_pagina_twitter"];?>
		
		<?php echo $form["link_pagina_youtube"];?>
		
		<?php echo $form["link_pagina_instagram"];?>
		
		<?php echo $form["link_pagina_linkedin"];?>
	</div>
</div>

