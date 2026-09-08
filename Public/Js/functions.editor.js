(function ($) {
	"use strict";

	function lineNumbers(count) {
		var numbers = [];
		var line;

		for (line = 1; line <= count; line++) {
			numbers.push(line);
		}

		return numbers.join("\n");
	}

	function updateLines(textarea, linesContent) {
		var count = textarea.value.split("\n").length;

		linesContent.textContent = lineNumbers(count);
	}

	function insertTab(textarea) {
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;

		textarea.value = textarea.value.substring(0, start) + "\t" + textarea.value.substring(end);
		textarea.selectionStart = textarea.selectionEnd = start + 1;
	}

	function indentSelection(textarea) {
		var value = textarea.value;
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var firstLine = value.lastIndexOf("\n", start - 1) + 1;
		var selected = value.substring(firstLine, end);
		var indented = "\t" + selected.replace(/\n/g, "\n\t");

		textarea.value = value.substring(0, firstLine) + indented + value.substring(end);
		textarea.selectionStart = start + 1;
		textarea.selectionEnd = end + indented.length - selected.length;
	}

	function outdentSelection(textarea) {
		var value = textarea.value;
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var firstLine = value.lastIndexOf("\n", start - 1) + 1;
		var selected = value.substring(firstLine, end);
		var removedBeforeStart = /^[\t ]/.test(selected) ? 1 : 0;
		var outdented = selected.replace(/^(\t| {1,4})/gm, "");

		textarea.value = value.substring(0, firstLine) + outdented + value.substring(end);
		textarea.selectionStart = Math.max(firstLine, start - removedBeforeStart);
		textarea.selectionEnd = end - (selected.length - outdented.length);
	}

	$.fn.editorCode = function () {
		return this.each(function () {
			var textarea = this;
			var $textarea = $(textarea);
			var $wrapper;
			var lines;
			var linesContent;

			if (!$textarea.is("textarea") || $textarea.data("editorCodeInitialized")) {
				return;
			}

			$textarea.data("editorCodeInitialized", true);
			$textarea.wrap('<div class="editorCode"></div>');
			$wrapper = $textarea.parent();
			lines = document.createElement("div");
			lines.className = "editorCode-lines";
			lines.setAttribute("aria-hidden", "true");
			linesContent = document.createElement("div");
			linesContent.className = "editorCode-lines-content";
			lines.appendChild(linesContent);
			$wrapper.prepend(lines);

			$textarea.addClass("editorCode-textarea");
			$textarea.attr("spellcheck", "false");
			updateLines(textarea, linesContent);

			$textarea.on("input.editorCode", function () {
				updateLines(textarea, linesContent);
			});

			$textarea.on("scroll.editorCode", function () {
				linesContent.style.transform = "translateY(" + (-textarea.scrollTop) + "px)";
			});

			$textarea.on("keydown.editorCode", function (event) {
				if (event.key !== "Tab" && event.keyCode !== 9) {
					return;
				}

				event.preventDefault();

				if (event.shiftKey) {
					outdentSelection(textarea);
				} else if (textarea.value.substring(textarea.selectionStart, textarea.selectionEnd).indexOf("\n") !== -1) {
					indentSelection(textarea);
				} else {
					insertTab(textarea);
				}

				$textarea.trigger("input");
			});
		});
	};
}(jQuery));
