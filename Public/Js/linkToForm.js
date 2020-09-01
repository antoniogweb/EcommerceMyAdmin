<!--

//create the form to traverse the filesystem
//formAction: the action of each form
//linkClassName: selectors of the links that have to be transformed into form
//submitNames: submit names of the del folder and del file forms
function linkToForm(formAction,linkClassName,submitNames)
{
	//set linkClassName
	linkClassName = (typeof linkClassName=="undefined") ? { "moveFolder":".moveFolder", "delFolder":".delFolder", "delFile":".delFile" } : linkClassName;

	//set submitNames
	submitNames = (typeof submitNames=="undefined") ? { "delFolder":"delFolderAction", "delFile":"delFileAction" } : submitNames;
	
	//function to create the form to traverse the filesystem
	function buildMoveForm(directoryName) {
		htmlForm = '<form name="traverseDynamicForm" action="' + formAction + '" method="POST"><input type="submit" value="moveForm" name="moveForm"><input type="hidden" name="directory" value="'+ directoryName + "\"></form>";
		return htmlForm;
	}

	//function to create the form to del a folder
	//itemName: the folder or file to del
	//currentDirectory: the current directory to move to
	//submitName: delFolderAction or delFileAction
	function buildDelItemForm(itemName,currentDirectory,submitName) {
		htmlForm = "<form name='traverseDynamicForm' action='" + formAction + "' method='POST'><input type='submit' value='moveForm' name='moveForm'><input type='hidden' name='directory' value='"+ currentDirectory + "'><input type='hidden' name='"+submitName+"' value='" + itemName + "'></form>";
		return htmlForm;
	}

	//move into the directory
	//formAction: the action of the form created dynamically
	function moveIntoFolder()
	{
		$(linkClassName["moveFolder"]).click(function() {
			//folder to move to
			dirPath = $(this).attr("href");
			$(this).attr({href : "#"});
			html = buildMoveForm(dirPath);
			$("#EGdynamicFolders").empty();
			$("#EGdynamicFolders").append(html);
			document.traverseDynamicForm.submit();
			return false;
		});
	}

	//linkClassName: the name of the class of the link
	//the name of the submit: delFolderAction or delFileAction
	function deleteFolder(linkClassName,submitName)
	{
		$(linkClassName).click(function() {
			//folder to del
			dirPath = $(this).attr("title");
			//folder to move to
			currDir = $(this).attr("href");
			$(this).attr({href : "#"});
			html = buildDelItemForm(dirPath,currDir,submitName);
			$("#EGdynamicFolders").empty();
			$("#EGdynamicFolders").append(html);
			document.traverseDynamicForm.submit();
			return false;
		});
	}

	this.traverse = function() {
		$("body").append("<div id='EGdynamicFolders' style='display:none;'></div>");
		moveIntoFolder();
		deleteFolder(linkClassName["delFolder"],submitNames["delFolder"]);
		deleteFolder(linkClassName["delFile"],submitNames["delFile"]);
	}
}

//-->
