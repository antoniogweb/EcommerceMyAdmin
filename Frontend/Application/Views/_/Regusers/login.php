<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Accedi")	=>	"",
);

$titoloPagina = gtext("Accedi");

include(tpf("/Elementi/Pagine/page_top.php"));

include(tpf(ElementitemaModel::p("LOGIN_TOP","", array(
	"titolo"	=>	"Pagina con avviso sopra al login",
	"percorso"	=>	"Elementi/Generali/LoginTop",
))));

include(tpf("/Regusers/login_form.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
