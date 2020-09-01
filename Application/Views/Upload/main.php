<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo $doctype;?>

<?php echo $notice;?>

<!--jquery-->
<script type="text/javascript" src="<?php echo $this->baseUrl;?>/Public/Js/jquery/jquery-1.8.3.min.js"></script>

<?php if ($this->viewArgs['is_popup']) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/tiny_mce/tiny_mce_popup.js"></script>

<script language="javascript" type="text/javascript">

	$(document).ready(function(){
		
		$(".EGfileBox_click").click(function(){
			
			$(".EGfileBox").removeClass("selected");
			$(this).parent().addClass("selected");
			return false;
		});
		
		$(".inserisci_button_submit").click(function(){
		
			if ($(".selected").length > 0)
			{
				var URL = $(".selected").find(".file_url").attr("rel");;
				var win = tinyMCEPopup.getWindowArg("window");

				// insert information now
				win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;

				// are we an image browser
				if (typeof(win.ImageDialog) != "undefined")
				{
					// we are, so update image dimensions and preview if necessary
					if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
					if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
				}
				
				tinyMCEPopup.close();
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

<?php if ($this->viewArgs['use_flash']) { ?>
<script type="text/javascript" src="<?php echo $this->baseUrl?>/Public/Js/uploadify_3_2_1/jquery.uploadify.js"></script>

<script type="text/javascript">
	
	var allowed = [ "jpg","jpeg","png","gif","bmp","pdf","doc","txt","docx" ];
	
	$(function() {
        $('#userfile').uploadify({
			'fileSizeLimit' : '60MB',
			'buttonText' : 'CARICA FILE',
			'formData'      : {'directory' : '<?php echo $base;?>','token':'<?php echo $token;?>'},
            'swf'      : '<?php echo $this->baseUrl."/Public/Js/uploadify_3_2_1/";?>uploadify.swf',
            'uploader' : '<?php echo $this->baseUrl."/upload/move";?>',
			'onUploadStart' : function(file) {
				if (jQuery.inArray(file.name.split('.').pop().toLowerCase(), allowed) == -1)
				{
					alert("il file " + file.name + " non sarà caricato perché la sua estensione non è ammessa");
					$('#userfile').uploadify("cancel",file.id);
				}
			},
            'onQueueComplete' : function(queueData) {
				window.location = "<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&uploadResult=success";?>"
			} 
        });
    });
    
</script>
<?php } ?>

<?php if ($this->viewArgs['mostra_upload']) { ?>
<div class='EGuploadFileBox'>
	<?php if (!$this->viewArgs['use_flash']) { ?>
	<form class="EGuploadFileBox_form" action='<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=uploadfile";?>' method='POST' enctype="multipart/form-data">
		
		<b>Carica file:</b>
		
	<?php } ?>
	
		<input id="userfile" name="userfile" type="file">
	
	<?php if (!$this->viewArgs['use_flash']) { ?>
		<input class="file_submit" type="submit" name="uploadFileAction" value="carica">
		<input type="hidden" name="MAX_FILE_SIZE" value="10000">
		<span class="loading_gif"><img src="<?php echo $this->baseUrl."/Public/Img/Icons/loading4.gif";?>" /></span>
		
	</form>
	<?php } ?>
</div>
<?php } ?>

<?php if ($this->viewArgs['mostra_crea']) { ?>
<div class='EGcreateFolderBox'>
	<form action='<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=createfolder";?>' method='POST'>
		<b>Crea una cartella:</b>
		<input type="text" name="folderName" value="">
		<input type="submit" name="uploadFileAction" value="crea">
	</form>
</div>
<?php } ?>

<div class='EGexternalBox'>
	<table width="100%">
		<?php if ($this->viewArgs['mostra_indietro']) { ?>
		<tr class='EGbackBox'>
			<td class="first" width="5%">
				<a class="parent_folder" href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$parentDir";?>"><img src="<?php echo $this->baseUrl?>/Public/Img/Icons/back.png" /></a>
			</td>
			<td class="second">Current directory: <b><?php echo $base."/".$currentDir;?></b></td>
			<?php if ($this->viewArgs['mostra_delete']) { ?>
			<td width="5%">&nbsp</td>
			<?php } ?>
		</tr>
		<?php } ?>
		
	<?php foreach ($folders as $folder) { ?>

		<tr class='EGfolderBox'>
			<td width="5%">
				<a class="inside_folder" href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=".$currentDir.$folder."/";?>"><img src="<?php echo $this->baseUrlSrc?>/Public/Img/Icons/folder.png" /></a>
			</td>
			<td>Folder name:<br /><b><?php echo $folder;?></b></td>
			<?php if ($this->viewArgs['mostra_delete']) { ?>
			<td width="8%">
				<a href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=delfolder&file=$folder";?>"><img src="<?php echo $this->baseUrl?>/Public/Img/Icons/delete.png" /></a>
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
						$img = "<img src='".$this->baseUrl."/upload/thumb/$file?base=$base&directory=$currentDir' />";
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
					echo "<img width='50px' src='".$this->baseUrl."/Public/Img/Icons/application-pdf.png'>\n";
				
				} else if (in_array($ext,$docExt)) {
					echo "<img width='50px' src='".$this->baseUrl."/Public/Img/Icons/ooffice.png'>\n";
				
				} else if (in_array($ext,$zipExt)) {
					echo "<img width='50px' src='".$this->baseUrl."/Public/Img/Icons/zip.png'>\n";
			
				} else {
					echo "<img width='50px' src='".$this->baseUrl."/Public/Img/Icons/file.png'>\n";
				}
				?>
			</td>
			<td class="EGfileBox_click">File name:<br /><b>
				<?php if ($this->viewArgs['mostra_url_completo']) { ?>
				<span class="file_url" rel="<?php echo $parentRoot."/".Parametri::$cartellaImmaginiGeneriche."$base/".$currentDir.$file;?>"><?php echo $parentRoot."/".Parametri::$cartellaImmaginiGeneriche."$base/".$currentDir.$file;?></span>
				<?php } else { ?>
				<span class="file_url" rel="<?php echo $parentRoot."/".Parametri::$cartellaImmaginiGeneriche."$base/".$currentDir.$file;?>"><pre><?php echo $file;?></pre></span>
				<?php } ?>
			</b></td>
			<?php if ($this->viewArgs['mostra_delete']) { ?>
			<td width="5%">
				<a class="delete_file_class" href="<?php echo $this->baseUrl."/upload/main".$this->viewStatus."?base=$base&directory=$currentDir&action=delfile&file=$file";?>"><img src="<?php echo $this->baseUrl?>/Public/Img/Icons/delete.png" /></a><span class="loading_gif_del"><img src="<?php echo $this->baseUrl."/Public/Img/Icons/loading4.gif";?>" /></span>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>

	<?php } ?>

	</table>
</div>

<?php if ($this->viewArgs['is_popup']) { ?>

<div class="inserisci_button">
	<input class="inserisci_button_submit" type="submit" name="inserisci" value="Inserisci" />
</div>

<?php } ?>