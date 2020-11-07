<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<?php include($this->viewPath("ordina"));?>

<section class="content-header">
	<h1>Gestione <?php echo $tabella;?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class='mainMenu'>
				<?php
				$pathMenu = ROOT."/Application/Views/".ucfirst($this->controller)."/".$this->action."_menu.php";
				
				if (file_exists($pathMenu))
					include($pathMenu);
				else if (isset($menu))
					echo $menu;
				?>
			</div>
			<div class="box">
				<div class="box-header with-border main">
					
					<?php
					$path = ROOT."/Application/Views/".ucfirst($this->controller)."/".$this->action."_filtri.php";
					
					if (file_exists($path))
						include($path);
					else if (isset($filtri))
						echo $filtri;
					?>
					
					<?php echo $notice;?>
					
					
					<?php echo $main;?>
					
					<!-- show the list of pages -->
					<div class="btn-group pull-right">
						<ul class="pagination no_vertical_margin">
							<?php echo $pageList;?>
						</ul>
					</div>
                </div>
			</div>
		</div>
	</div>
</section>
