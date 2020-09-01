<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p class="breadcrumb"><span class="testo_sei_qui">sei qui:</span> <a href="<?php echo $this->baseUrl;?>">Home</a> » promozioni</p>

<div id="main">

	<h1>Prodotti in promozione</h1>
	
	<?php include(ROOT."/Application/Views/Contenuti/items.php");?>
	
</div>