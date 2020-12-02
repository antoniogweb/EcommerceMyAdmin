<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div id="page-title-bar" class="page-title-bar">
	<div class="container">
		<div class="wrap w-100 d-flex align-items-center text-center">
			<div class="page-title-bar-inner d-flex align-self-center flex-column w-100 text-center">
				<div class="breadcrumb mb-0 w-100 order-last">
					<!-- Breadcrumb NavXT 6.3.0 -->
					<?php if ($islogged) { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <a href="<?php echo $this->baseUrl."/area-riservata";?>"><?php echo gtext("Area riservata");?></a> » <a href="<?php echo $this->baseUrl."/ordini-effettuati";?>"><?php echo gtext("Ordini effettuati");?></a> » <?php echo gtext("Resoconto Ordine");?></p>
					<?php } else { ?>
					<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>"><?php echo gtext("Home");?></a> » <?php echo gtext("Resoconto Ordine");?></p>
					<?php } ?>
				</div>
				<div class="page-header  mb-2 w-100 order-first">
					<h1 class="page-title"><?php echo gtext("Resoconto dell'ordine");?></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($islogged) { $isFromAreariservata = true;}?>

