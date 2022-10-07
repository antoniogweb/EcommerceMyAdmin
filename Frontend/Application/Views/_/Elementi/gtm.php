<?php if (!defined('EG')) die('Direct access not allowed!');
if (v("codice_gtm")) {
	echo htmlentitydecode(v("codice_gtm"));
}

include(tpf("/Elementi/Analytics/analytics".v("versione_google_analytics").".php"));
