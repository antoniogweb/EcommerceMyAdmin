<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div id="main" class="mainwrap">
	<div class="main clearfix">
		<div class="content fullwidth woocommerce">
			<p class="breadcrumb"><span class="testo_sei_qui"></span> <a href="<?php echo $this->baseUrl;?>">Home</a> » <?php echo $breadcrumb;?></p>
		</div>
	</div>
</div>


<div class="single single-product postid-10100 raindrop woocommerce woocommerce-page">
<div class="mainwrap">
<div class="main clearfix">
<div class="content fullwidth">
<div class="">
<div class="posttext">
<div id="container">
<div id="content" role="main">

<div id="main">
	<?php foreach ($pages as $p) { ?>
	<div class="dettaglio_pagina">
		<div class="pagecontentContent-title"><h1><?php echo $p["pages"]["title"];?></h1></div><br /><br />
		
		<div>
			<?php echo htmlentitydecode(attivaModuli($p["pages"]["description"]));?>
		</div>
	</div>
	<?php } ?>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>