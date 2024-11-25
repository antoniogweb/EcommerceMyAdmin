<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<section class="content-header">
	<h1><?php echo gtext("Carica i cookie")?></h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="mainMenu">
				<a title="torna" role="button" class="btn btn-default make_spinner" href="<?php echo $this->baseUrl."/cookiearchivio/main";?>"><i class="fa fa-arrow-circle-left"></i> Torna</a>
			</div>
			<div class="box">
				<div class="box-header with-border main">
					<form action="<?php echo $this->baseUrl."/cookiearchivio/carica";?>" method="POST">
						<textarea name="cookie" class="form-control"></textarea><br />
						<button type="submit" class="btn btn-success">Invia</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
 
