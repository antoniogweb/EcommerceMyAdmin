(function (window) {
	"use strict";

	var activeEditor = null;
	var uploadManagerWatcher = null;
	var imageExtensions = /\.(?:avif|gif|jpe?g|png|svg|webp)(?:[?#].*)?$/i;

	function fileNameFromUrl(url) {
		var cleanUrl = url.split(/[?#]/)[0];
		var name = cleanUrl.substring(cleanUrl.lastIndexOf("/") + 1);

		try {
			return decodeURIComponent(name) || url;
		} catch (error) {
			return name || url;
		}
	}

	function openUploadManager(editor) {
		if (activeEditor && activeEditor !== editor) {
			activeEditor.s.restore();
		}

		activeEditor = editor;
		editor.s.save();

		var managerUrl = baseUrl + "/upload/main/1/1/1/1/0/0/1/0/1/0/1?base=";
		var popup = window.open(
			managerUrl,
			"editorVisualeUploadManager",
			"width=900,height=650,resizable=yes,scrollbars=yes"
		);

		if (popup) {
			popup.focus();
			window.clearInterval(uploadManagerWatcher);
			uploadManagerWatcher = window.setInterval(function () {
				if (popup.closed) {
					window.clearInterval(uploadManagerWatcher);
					uploadManagerWatcher = null;

					if (activeEditor) {
						activeEditor.s.restore();
						activeEditor = null;
					}
				}
			}, 500);
		} else {
			activeEditor.s.restore();
			activeEditor = null;
		}
	}

	window.editorVisualeSelezionaFile = function (url) {
		if (!activeEditor || typeof url !== "string" || url === "") {
			return false;
		}

		activeEditor.s.restore();

		if (imageExtensions.test(url)) {
			activeEditor.s.insertImage(url, null, null);
		} else {
			var link = activeEditor.createInside.element("a");
			link.href = url;
			link.textContent = fileNameFromUrl(url);
			activeEditor.s.insertNode(link);
		}

		activeEditor.synchronizeValues();
		activeEditor = null;
		window.clearInterval(uploadManagerWatcher);
		uploadManagerWatcher = null;

		return true;
	};

	var controls = {};
	var buttons = [
		"bold", "italic", "underline", "strikethrough", "ul", "ol",
		"left", "center", "right", "justify", "link", "unlink"
	];

	if (String(window.permetti_upload_generico) === "1") {
		controls.archivioFile = {
			name: "archivioFile",
			icon: "image",
			tooltip: "Inserisci immagine o file",
			exec: function (editor) {
				openUploadManager(editor);
			}
		};
		buttons.push("archivioFile");
	}

	buttons = buttons.concat([
		"paragraph", "brush", "subscript", "superscript", "table", "hr",
		"undo", "redo", "source"
	]);

	window.editorVisualeConfig = {
		language: "it",
		width: "100%",
		height: 250,
		enter: "br",
		useSplitMode: false,
		buttons: buttons,
		controls: controls,
		events: {
			afterInit: function () {
				if (typeof window.aggAlteIfr === "function") {
					window.aggAlteIfr();
				}
			}
		}
	};

	window.editorVisuale = function (selettore) {
		document.querySelectorAll(selettore).forEach(function (textarea) {
			if (!Jodit.isJoditAssigned(textarea)) {
				Jodit.make(textarea, editorVisualeConfig);
			}
		});
	};
})(window);
