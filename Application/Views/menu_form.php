<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<script type="text/javascript">

function showItem(sel)
{
	switch(sel)
	{
		case "esterno":
			$(".cat_Select").css("display","none");
			$(".cont_Select").css("display","none");
			$(".alias_Select").css("display","block");
			$(".file_Select").css("display","none");
			break;
		case "libero":
			$(".cat_Select").css("display","none");
			$(".cont_Select").css("display","none");
			$(".alias_Select").css("display","block");
			$(".file_Select").css("display","none");
			break;
		case "cat":
			$(".alias_Select").css("display","none");
			$(".cont_Select").css("display","none");
			$(".cat_Select").css("display","block");
			$(".file_Select").css("display","none");
			break;
		case "cont":
			$(".cat_Select").css("display","none");
			$(".alias_Select").css("display","none");
			$(".cont_Select").css("display","block");
			$(".file_Select").css("display","none");
			break;
		case "custom":
			$(".cat_Select").css("display","none");
			$(".alias_Select").css("display","none");
			$(".cont_Select").css("display","none");
			$(".file_Select").css("display","block");
			break;
		case "home":
			$(".cat_Select").css("display","none");
			$(".alias_Select").css("display","none");
			$(".cont_Select").css("display","none");
			$(".file_Select").css("display","none");
			break;
		case "nessuno":
			$(".cat_Select").css("display","none");
			$(".alias_Select").css("display","none");
			$(".cont_Select").css("display","none");
			$(".file_Select").css("display","none");
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
