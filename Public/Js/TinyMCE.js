tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		editor_selector : "contentArea",
		convert_urls : false,
// 		editor_deselector : "mceNoEditor",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,spellchecker,media",
       
// 		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,image,removeformat,anchor,blockquote,code",
// 		theme_advanced_buttons2 : "",
// 		theme_advanced_buttons3 : "",
			 
		// Theme options
		theme_advanced_buttons1 : "justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media",
		theme_advanced_buttons4 : "styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,template,|,forecolor,backcolor,bold,italic,underline,strikethrough,|,insertdate,inserttime",
		
		file_browser_callback : "ajaxfilemanager",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
// 		theme_advanced_resizing : true,
		
// 		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
// 		apply_source_formatting : true,
// 		relative_urls : false,
// 		remove_script_host : false,
		document_base_url : "<?=SITE_URL?>",
		languages : "it",
			 
		// Example content CSS (should be your site CSS)
// 		content_css : "../stili_editor.css",
		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
	
	function ajaxfilemanager(field_name, url, type, win) {
		var ajaxfilemanagerurl = base_url + "/Public/Js/tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
		switch (type) {
			case "image":
			ajaxfilemanagerurl += "?type=img";
			break;
			case "media":
			ajaxfilemanagerurl += "?type=media";
			break;
			case "flash": //for older versions of tinymce
			ajaxfilemanagerurl += "?type=media";
			break;
			case "file":
			ajaxfilemanagerurl += "?type=files";
			break;
			default:
			return false;
		}
		var fileBrowserWindow = new Array();
		fileBrowserWindow["file"] = ajaxfilemanagerurl;
		fileBrowserWindow["title"] = "Ajax File Manager";
		fileBrowserWindow["width"] = "782";
		fileBrowserWindow["height"] = "440";
		fileBrowserWindow["resizable "] = "yes";
		fileBrowserWindow["inline"] = "yes";
		fileBrowserWindow["close_previous"] = "no";
		tinyMCE.activeEditor.windowManager.open(fileBrowserWindow, {
			window : win,
			input : field_name
		});

		return false;
	}
