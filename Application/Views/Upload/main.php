<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo $doctype;?>

<?php echo $notice;?>

<!--jquery-->
<script src="<?php echo $this->baseUrlSrc.'/Public/Js/vendor/jquery/';?>jquery.min.js"></script>

<?php if ($this->viewArgs['is_popup']) { ?>
<script language="javascript" type="text/javascript">

	$(document).ready(function(){
		
		$(".EGfileBox_click").click(function(){
			$(".EGfileBox").removeClass("selected");
			$(this).closest(".EGfileBox").addClass("selected");
			$(".inserisci_button").addClass("is-visible").attr("aria-hidden", "false");
			return false;
		});
		
		$(".inserisci_button_submit").click(function(){
		
			if ($(".selected").length > 0)
			{
				var URL = $(".selected").find(".file_url").attr("rel");

				if (window.opener && !window.opener.closed &&
					typeof window.opener.editorVisualeSelezionaFile === "function" &&
					window.opener.editorVisualeSelezionaFile(URL))
				{
					window.close();
				}
			}
			return false;
		});
		
	});

</script>
<?php } ?>


<script language="javascript" type="text/javascript">

	$(document).ready(function(){
		
		$(".file_submit").click(function(){
			
			$(this).css("display","none");
			$(".loading_gif img").css("visibility","visible");
			
		});
		
		$(".delete_file_class").click(function(){
			
// 			$(this).css("display","none").parent().find(".loading_gif_del img").css("visibility","visible");
			
		});
	});

</script>

<main class="upload-manager">

<div class="upload-actions">
<?php if ($this->viewArgs["mostra_upload"]) { ?>
<section class="EGuploadFileBox upload-action-card">
	
	<h2><?php echo gtextPlain("Carica file");?></h2>
	<form class="EGuploadFileBox_form" action='<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=uploadfile";?>' method='POST' enctype="multipart/form-data">
		
		
		<input id="userfile" name="userfile" type="file">
	
		<input class="file_submit" type="submit" name="uploadFileAction" value="<?php echo gtextAttr("carica", false);?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="10000">
		<span class="loading_gif"><img src="<?php echo $this->baseUrlSrc."/Public/Img/Icons/loading4.gif";?>" /></span>
	</form>

</section>
<?php } ?>

<?php if ($this->viewArgs["mostra_crea"]) { ?>
<section class="EGcreateFolderBox upload-action-card">
	<h2><?php echo gtextPlain("Crea una cartella");?></h2>
	<form action='<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=createfolder";?>' method='POST'>
		<input type="text" name="folderName" value="">
		<input type="submit" name="uploadFileAction" value="<?php echo gtextAttr("crea", false);?>">
	</form>
</section>
<?php } ?>
</div>

<section class="EGexternalBox upload-browser-card">
	<div class="upload-browser-heading">
		<h2><?php echo gtextPlain("File e cartelle");?></h2>
		<span><?php echo gtextPlain("Current directory");?>: <strong><?php echo $base."/".$currentDir;?></strong></span>
	</div>
	<table class="upload-browser" width="100%">
		<?php if ($this->viewArgs['mostra_indietro']) { ?>
		<tr class='EGbackBox'>
			<td class="first" width="5%">
				<a class="parent_folder" href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$parentDir";?>"><i class="fa fa-arrow-left fa-lg" aria-hidden="true"></i></a>
			</td>
			<td class="second"><?php echo gtextPlain("Current directory");?>: <b><?php echo $base."/".$currentDir;?></b></td>
		</tr>
		<?php } ?>
		
	<?php foreach ($folders as $folder) { ?>

		<tr class='EGfolderBox'>
			<td width="5%">
				<a class="inside_folder" href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=".$currentDir.$folder."/";?>"><i class="fa fa-folder fa-2x" aria-hidden="true"></i></a>
			</td>
			<td><?php echo gtextPlain("Folder name");?>:<br /><b><?php echo $folder;?></b></td>
			<?php if ($this->viewArgs['mostra_delete']) { ?>
			<td width="8%">
				<a href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=delfolder&file=$folder"."&csrf=".User::$csrfToken;?>"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>
			</td>
			<?php } ?>
		</tr>

	<?php } ?>

	<?php foreach ($files as $file) { ?>

		<?php
		$extArray = explode('.', $file);
		$ext =  strtolower(end($extArray));
		$imgExt= array('jpg','jpeg','png','gif');
		if ($this->viewArgs['tutti_i_tipi'] or in_array($ext,$imgExt))
		{
		?>
		<tr class='EGfileBox'>
			<td class="EGfileBox_click" width="5%">
				<?php 
				$pdfExt= array('pdf');
				$docExt= array('doc','docx','ods');
				$zipExt= array('zip');
				if (in_array($ext,$imgExt))
				{
					if ($this->viewArgs['use_dynamic_thumbs'])
					{
						$img = "<img src='".$this->baseUrlSrc."/upload/thumb/$file?base=$base&directory=$currentDir' />";
					}
					else
					{
						$img = "<img width='50px' src='".$parentRoot."/".Parametri::$cartellaImmaginiGeneriche."$base/".$currentDir.$file."'>";
					}
					
					if ($this->viewArgs['link_immagini']) {
					echo "<a class='imageFile' href='#'>$img</a>\n";
					}
					else
					{
						echo "$img\n";
					}
					
				} else if (in_array($ext,$pdfExt)) {
					echo "<i class='fa fa-file-pdf-o file-type-icon' aria-hidden='true'></i>\n";
				
				} else if (in_array($ext,$docExt)) {
					echo "<i class='fa fa-file-text-o file-type-icon' aria-hidden='true'></i>\n";
				
				} else if (in_array($ext,$zipExt)) {
					echo "<i class='fa fa-file-archive-o file-type-icon' aria-hidden='true'></i>\n";
			
				} else {
					echo "<i class='fa fa-file-o file-type-icon' aria-hidden='true'></i>\n";
				}
				?>
			</td>
			<td class="EGfileBox_click">File name:<br /><b>
				<span class="file_url" rel="<?php echo $parentRoot."/".Parametri::$cartellaImmaginiGeneriche."$base/".$currentDir.$file;?>"><pre><?php echo $file;?></pre></span>
			</b></td>
			<?php if ($this->viewArgs['mostra_delete']) { ?>
			<td width="5%">
				<a class="delete_file_class" href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=delfile&file=$file"."&csrf=".User::$csrfToken;?>"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a><span class="loading_gif_del"><img src="<?php echo $this->baseUrlSrc."/Public/Img/Icons/loading4.gif";?>" /></span>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>

	<?php } ?>

	</table>
<?php if ($this->viewArgs["is_popup"]) { ?>
	<div class="inserisci_button" aria-hidden="true">
		<input class="inserisci_button_submit" type="submit" name="inserisci" value="<?php echo gtextAttr("Inserisci", false);?>" />
	</div>
<?php } ?>
</section>

</main>
