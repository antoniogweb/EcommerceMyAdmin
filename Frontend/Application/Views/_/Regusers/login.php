<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Accedi")	=>	"",
);

$titoloPagina = gtext("Accedi");

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf("/Regusers/login_form.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
