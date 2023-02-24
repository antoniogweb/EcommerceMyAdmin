<?php if (!defined('EG')) die('Direct access not allowed!');

if (v("codice_gtm_analytics"))
{
	if (isset($pages) && count($pages) > 0)
	{
		include(tpf("/Elementi/Analytics/Cache/analytics_impressioni_lista".v("versione_google_analytics").".php"));
	}
}
