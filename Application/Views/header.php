<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

	<title><?php echo $title;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if (v("favicon_url")) { ?>
	<link rel="icon" href="<?php echo v("favicon_url");?>" type="image/png"/>
	<?php } ?>
	
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseUrlSrc.'/Public/Css/panel.css?v='.rand(1,100000);?>">
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->baseUrlSrc.'/Public/Css/main.css?v='.rand(1,100000);?>">
	<link rel="stylesheet" type="text/css" media="print" href="<?php echo $this->baseUrlSrc."/Public/Css/";?>print.css?v=<?php echo rand(1,100000);?>" />

	<script>
		var baseUrl = "<?php echo $this->baseUrl;?>";
		var baseUrlSrc = "<?php echo $this->baseUrlSrc;?>";
		var parentBaseUrl = "<?php echo $parentRoot;?>";
		var controllerName = "<?php echo $this->controller;?>";
		var actionName = "<?php echo $this->action;?>";
		var viewStatus = "<?php echo $this->viewStatus;?>";
		var partial = <?php echo partial() ? "true" : "false";?>;
	</script>

	<!--jquery-->
	<script src="<?php echo $this->baseUrlSrc.'/Public/Js/jquery/';?>jquery-3.6.0.min.js"></script>
	<script src="<?php echo $this->baseUrlSrc.'/Public/Js/jquery/';?>jquery-migrate-1.4.1.min.js"></script>
	
	<link rel="stylesheet" href="<?php echo $this->baseUrlSrc;?>/Public/Js/jquery-ui-1.12.1.custom/jquery-ui.css" />
	<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/Public/Js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
	<script src="<?php echo $this->baseUrlSrc.'/Public/Js/';?>ajaxQueue.js"></script>
	<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/Public/Js/functions.js?v=<?php echo rand(1,100000);?>"></script>
	<script type="text/javascript" src="<?php echo $this->baseUrlSrc;?>/Public/Js/jquery_easygiant.js?v=<?php echo rand(1,100000);?>"></script>
	
	<!-- 	CSS tema -->
	<link rel="stylesheet" href="<?php echo $this->baseUrlSrc?>/Public/Js/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo $this->baseUrlSrc?>/Public/Js/AdminLTE-2.3.0/dist/css/AdminLTE.min.css">
	
	<link rel="stylesheet" href="<?php echo $this->baseUrlSrc?>/Public/Js/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css">
	
    <!-- Custom styles for this template -->
    <link href="<?php echo $this->baseUrlSrc;?>/Public/Css/dashboard.css?v=<?php echo rand(1,100000);?>" rel="stylesheet">
    
    <script src="<?php echo $this->baseUrlSrc;?>/Public/Js/bootstrap-3.1.1-dist/js/bootstrap.min.js"></script>
    <script src="<?php echo $this->baseUrlSrc;?>/Public/Js/AdminLTE-2.3.0/dist/js/app.min.js"></script>
    
    <script src="<?php echo $this->baseUrlSrc;?>/Public/Js/Respond-master/dest/respond.src.js"></script>
    
    <link href="<?php echo $this->baseUrlSrc;?>/Public/Css/icons/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link href="<?php echo $this->baseUrlSrc;?>/Public/Js/bootstrap-colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
    <script src="<?php echo $this->baseUrlSrc;?>/Public/Js/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
    
    <?php if (defined("APPS")) {
		foreach (APPS as $app)
		{
			$path = ROOT."/Application/Apps/".ucfirst($app)."/Public/";
			
			if (file_exists($path."app_editor.js")) { ?>
				<script src="<?php echo $this->baseUrlSrc."/Application/Apps/".ucfirst($app);?>/Public/app_editor.js?v=<?php echo rand(1,100000);?>"></script>
			<?php }
		}
	} ?>
    
    <?php if (partial()) { ?>
    <style>
		.content-wrapper
		{
			margin-left:0px;
		}
    </style>
    <?php } ?>
    

    <script>
	$(document).ready(function(){
		var iframeHeight = window.innerHeight - 160;
		$("iframe.iframe_dialog").attr("height",iframeHeight + "px");
	});
	</script>
    
</head>

<!--[if lt IE 7 ]> <body class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <body class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <body class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <body class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <body class="hold-transition skin-blue sidebar-mini" <?php if (partial()) { ?>style="padding-top:0px;"<?php } ?> <!--<![endif]-->
	
	<div class="wrapper">

		<?php if (!partial()) { ?>
		<header class="main-header">
			<!-- Logo -->
			<?php if (User::$logged and strcmp($this->action,'logout') !== 0) {?>
			<a class="logo" href="<?php echo $this->baseUrlSrc;?>/panel/main"><?php echo Parametri::$nomeNegozio;?></a>
			<?php } else { ?>
			<a class="logo" href="<?php echo $this->baseUrlSrc;?>"><?php echo Parametri::$nomeNegozio;?></a>
			<?php } ?>

			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-fixed-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<?php if (User::$logged and strcmp($this->action,'logout') !== 0) {?>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> <?php if (!User::$isMobile) { ?><?php echo User::$name;?> <?php } ?><span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="<?php echo $this->baseUrl.'/password/form';?>"><span class="glyphicon glyphicon-cog"></span> <?php echo gtext("Modifica password")?></a></li>
								<li><a href="<?php echo $this->baseUrl.'/users/logout';?>"><span class="glyphicon glyphicon-off"></span> <?php echo gtext("Esci")?></a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li <?php if (strcmp($sezionePannello,"sito") === 0) { ?>class="active"<?php } ?>>
							<a href="<?php echo $this->baseUrl.'/'.v("link_cms");?>"><span class="glyphicon glyphicon-cloud"></span>
							<?php if (!User::$isMobile) { ?>
							<?php echo gtext("CMS")?>
							<?php } ?>
							</a>
						</li>
						<?php if (v("attiva_menu_ecommerce")) { ?>
						<li <?php if (strcmp($sezionePannello,"ecommerce") === 0) { ?>class="active"<?php } ?>>
							<a href="<?php echo $this->baseUrl.'/prodotti/main';?>"><span class="glyphicon glyphicon-shopping-cart"></span>
							<?php if (!User::$isMobile) { ?>
							<?php echo gtext("E-commerce")?>
							<?php } ?>
							</a>
						</li>
						<?php } ?>
						<li <?php if (strcmp($sezionePannello,"utenti") === 0) { ?>class="active"<?php } ?>>
							<a href="<?php echo $this->baseUrl.'/users/main';?>"><span class="glyphicon glyphicon-cog"></span>
							<?php if (!User::$isMobile) { ?>
							<?php echo gtext("Preferenze")?>
							<?php } ?>
							</a>
						</li>
					</ul>
					<?php } ?>
				</div>
			</nav>
		</header>
		<?php } ?>
