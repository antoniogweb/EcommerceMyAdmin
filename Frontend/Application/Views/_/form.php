<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php
$breadcrumb = array(
	gtext("Home") 		=> $this->baseUrl,
	gtext("Area riservata")	=>	"",
);

$titoloPagina = gtext("Area riservata");

include(tpf("/Elementi/Pagine/page_top.php"));

$attiva = "dashboard";

include(tpf("/Elementi/Pagine/riservata_top.php"));
?>
	<?php echo $azioni;?>
	
	<div class="uk-margin">
		<?php $flash = flash("notice");?>
		<?php echo $flash;?>
		<?php if (!$flash) echo $notice;?>
	</div>
	
	<!-- show the table -->
	<div class='uk-margin'>
		<?php
		$applicationPath = $this->application ? "Apps/".ucfirst($this->application)."/" : "";
		
		$path = ROOT."/Application/Views/".ucfirst($this->controller)."/".$this->action."_scaffold_main.php";
		
		if (file_exists($path))
			include($path);
		else
			echo $main;
		?>
	</div>
<?php
include(tpf("/Elementi/Pagine/riservata_bottom.php"));

include(tpf("/Elementi/Pagine/page_bottom.php"));
