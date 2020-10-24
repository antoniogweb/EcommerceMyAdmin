<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
if (defined("CAMPI_AGGIUNTIVI_PAGINE") && isset(CAMPI_AGGIUNTIVI_PAGINE[$sectionCampiAggiuntivi]))
{
	foreach (CAMPI_AGGIUNTIVI_PAGINE[$sectionCampiAggiuntivi] as $sec => $el)
	{
		echo $form[$sec];
	}
}
