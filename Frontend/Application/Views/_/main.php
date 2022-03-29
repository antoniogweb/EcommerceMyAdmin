<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext($tabella)	=>	"",
);

$titoloPagina = gtext($tabella);

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "dashboard";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
<div class='uk-margin'>
	<?php
	$pathMenu = ROOT."/Application/Views/".ucfirst($this->controller)."/".$this->action."_menu.php";
	
	if (file_exists($pathMenu))
		include($pathMenu);
	else if (isset($menu))
		echo $azioni;
	?>
</div>

<?php if (!nofiltri()) { ?>
<?php
$path = ROOT."/Application/Views/".ucfirst($this->controller)."/".$this->action."_filtri.php";

if (file_exists($path))
	include($path);
else if (isset($filtri))
	echo $filtri;
?>
<?php } ?>

<?php
$path = ROOT."/Application/Views/".ucfirst($this->controller)."/main_action.php";

if (file_exists($path))
	include($path);
?>

<div class="uk-margin">
<?php $flash = flash("notice");?>
<?php echo $flash;?>
<?php if (!$flash) echo $notice;?>
</div>

<div class="uk-margin uk-overflow-auto">
	<?php echo $main;?>
</div>

<!-- show the list of pages -->
<ul class="uk-pagination uk-flex-right uk-margin-medium-top">
	<?php echo $pageList;?>
</ul>

<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
