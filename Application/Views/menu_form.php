<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

var arraySelettore = [
	".cat_Select",
	".cont_Select",
	".alias_Select",
	".file_Select",
	".marchi_Select",
	".tag_Select",
];

function displayItem(selettore)
{
	for (var i = 0; i < arraySelettore.length; i++)
	{
		if (selettore == arraySelettore[i])
			$(arraySelettore[i]).css("display","block");
		else
			$(arraySelettore[i]).css("display","none");
	}
}

function showItem(sel)
{
	switch(sel)
	{
		case "esterno":
			displayItem(".alias_Select");
			break;
		case "libero":
			displayItem(".alias_Select");
			break;
		case "cat":
			displayItem(".cat_Select");
			break;
		case "cont":
			displayItem(".cont_Select");
			break;
		case "marchio":
			displayItem(".marchi_Select");
			break;
		case "tag":
			displayItem(".tag_Select");
			break;
		case "custom":
			displayItem(".file_Select");
			break;
		case "home":
			displayItem("");
			break;
		case "nessuno":
			displayItem("");
			break;
	}
}

$(document).ready(function() {

	showItem($("#tipo_link").val());
	
	$("#tipo_link").change(function(){
	
		var sel = $(this).val();
		
		showItem(sel);
	
	});

});

</script>

<section class="content-header">
	<h1><?php echo gtext($titoloMenu);?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class='mainMenu'>
				<?php echo $menu;?>
			</div>
			<div class="box">
				<div class="box-header with-border main">
					<?php echo $notice;?>
					
					<?php echo $main;?>
                </div>
			</div>
		</div>
	</div>
</section>
