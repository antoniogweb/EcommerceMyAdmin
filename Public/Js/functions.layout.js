(function($) {
	"use strict";

	if (!$)
		return;

	var modalDuration = 300;
	var collapseDuration = 350;

	function reflow(element) {
		return element.offsetHeight;
	}

	function trigger($element, name) {
		var event = $.Event(name);
		$element.trigger(event);
		return !event.isDefaultPrevented();
	}

	/* Modal */
	function showModal($modal) {
		if ($modal.hasClass("in") || !trigger($modal, "show.bs.modal"))
			return;

		var oldTimer = $modal.data("layout-modal-timer");
		if (oldTimer)
			window.clearTimeout(oldTimer);

		var $backdrop = $("<div class=\"modal-backdrop fade\"></div>").appendTo(document.body);
		$modal.data("layout-modal-backdrop", $backdrop);
		$("body").addClass("modal-open");
		$modal.css("display", "block").attr({
			"aria-hidden": "false",
			"aria-modal": "true"
		});

		reflow($backdrop[0]);
		reflow($modal[0]);
		$backdrop.addClass("in");
		$modal.addClass("in").trigger("focus");

		var timer = window.setTimeout(function() {
			$modal.removeData("layout-modal-timer");
			if ($modal.hasClass("in"))
				$modal.trigger("shown.bs.modal");
		}, modalDuration);
		$modal.data("layout-modal-timer", timer);
	}

	function hideModal($modal) {
		if (!$modal.hasClass("in") || !trigger($modal, "hide.bs.modal"))
			return;

		var oldTimer = $modal.data("layout-modal-timer");
		if (oldTimer)
			window.clearTimeout(oldTimer);

		var $backdrop = $modal.data("layout-modal-backdrop");
		$modal.removeClass("in").attr({
			"aria-hidden": "true",
			"aria-modal": null
		});
		if ($backdrop)
			$backdrop.removeClass("in");

		var timer = window.setTimeout(function() {
			if ($modal.hasClass("in"))
				return;

			$modal.css("display", "none").removeData("layout-modal-timer");
			if ($backdrop)
				$backdrop.remove();
			$modal.removeData("layout-modal-backdrop");
			if ($(".modal.in").length === 0)
				$("body").removeClass("modal-open");
			$modal.trigger("hidden.bs.modal");
		}, modalDuration);
		$modal.data("layout-modal-timer", timer);
	}

	$.fn.modal = function(option) {
		return this.each(function() {
			var $modal = $(this);

			if (option === "hide")
				hideModal($modal);
			else if (option === "toggle")
				$modal.hasClass("in") ? hideModal($modal) : showModal($modal);
			else if (option === "show" || (typeof option === "object" && option.show !== false && !option.refresh))
				showModal($modal);
		});
	};

	$(document).on("click", "[data-dismiss='modal']", function(event) {
		event.preventDefault();
		hideModal($(this).closest(".modal"));
	});

	$(document).on("click", ".modal", function(event) {
		if (event.target === this)
			hideModal($(this));
	});

	$(document).on("keyup", function(event) {
		if (event.which === 27)
			hideModal($(".modal.in").last());
	});

	/* Dropdown */
	function closeDropdowns($exception) {
		$(".dropdown.open").each(function() {
			var $dropdown = $(this);
			if ($exception && $dropdown.is($exception))
				return;
			if (!trigger($dropdown, "hide.bs.dropdown"))
				return;

			$dropdown.removeClass("open");
			$dropdown.children("[data-toggle='dropdown']").attr("aria-expanded", "false");
			$dropdown.trigger("hidden.bs.dropdown");
		});
	}

	$(document).on("click", function(event) {
		var $toggle = $(event.target).closest("[data-toggle='dropdown']");

		if ($toggle.length) {
			event.preventDefault();
			var $dropdown = $toggle.closest(".dropdown");
			var isOpen = $dropdown.hasClass("open");

			closeDropdowns(isOpen ? null : $dropdown);
			if (!isOpen && trigger($dropdown, "show.bs.dropdown")) {
				$dropdown.addClass("open");
				$toggle.attr("aria-expanded", "true");
				$dropdown.trigger("shown.bs.dropdown");
			}
			return;
		}

		if ($(event.target).closest(".dropdown-menu input, .dropdown-menu textarea").length === 0)
			closeDropdowns();
	});

	/* Collapse */
	function collapseDisplay($collapse) {
		if ($collapse.is("tbody"))
			return "table-row-group";
		if ($collapse.is("tr"))
			return "table-row";
		return "block";
	}

	function collapseTriggers($collapse) {
		var id = $collapse.attr("id");
		if (!id)
			return $();

		return $("[data-toggle='collapse']").filter(function() {
			return $(this).attr("data-target") === "#" + id || $(this).attr("href") === "#" + id;
		});
	}

	function showCollapse($collapse) {
		if ($collapse.hasClass("in") || $collapse.hasClass("collapsing") || !trigger($collapse, "show.bs.collapse"))
			return;

		$collapse.removeClass("collapse").addClass("collapsing").css({
			display: collapseDisplay($collapse),
			height: 0
		});
		collapseTriggers($collapse).attr("aria-expanded", "true").removeClass("collapsed");
		reflow($collapse[0]);
		$collapse.height($collapse[0].scrollHeight);

		window.setTimeout(function() {
			if (!$collapse.hasClass("collapsing"))
				return;
			$collapse.removeClass("collapsing").addClass("collapse in").css({ display: "", height: "" });
			$collapse.trigger("shown.bs.collapse");
		}, collapseDuration);
	}

	function hideCollapse($collapse) {
		if (!$collapse.hasClass("in") || $collapse.hasClass("collapsing") || !trigger($collapse, "hide.bs.collapse"))
			return;

		$collapse.height($collapse.height()).removeClass("collapse in").addClass("collapsing");
		collapseTriggers($collapse).attr("aria-expanded", "false").addClass("collapsed");
		reflow($collapse[0]);
		$collapse.height(0);

		window.setTimeout(function() {
			if (!$collapse.hasClass("collapsing"))
				return;
			$collapse.removeClass("collapsing").addClass("collapse").css({ display: "", height: "" });
			$collapse.trigger("hidden.bs.collapse");
		}, collapseDuration);
	}

	$(document).on("click", "[data-toggle='collapse']", function(event) {
		var $trigger = $(this);
		var selector = $trigger.attr("data-target") || $trigger.attr("href");

		if (!selector || selector.charAt(0) !== "#")
			return;

		var $collapse = $(selector);
		if (!$collapse.length)
			return;

		event.preventDefault();
		$collapse.hasClass("in") ? hideCollapse($collapse) : showCollapse($collapse);
	});

	/* File upload */
	function appendUploadFormData(formData, values) {
		if ($.isArray(values)) {
			$.each(values, function(index, value) {
				if (value && value.name !== undefined)
					formData.append(value.name, value.value);
			});
			return;
		}

		$.each(values || {}, function(name, value) {
			if ($.isArray(value)) {
				$.each(value, function(index, item) {
					formData.append(name, item === null || item === undefined ? "" : item);
				});
			} else {
				formData.append(name, value === null || value === undefined ? "" : value);
			}
		});
	}

	function uploadCallback($input, options, name, data) {
		var event = $.Event("fileupload" + name);
		var result;

		$input.trigger(event, [data]);
		if ($.isFunction(options[name]))
			result = options[name].call($input[0], event, data);

		return result !== false && !event.isDefaultPrevented();
	}

	function parseUploadResult(xhr) {
		if (xhr.response && typeof xhr.response === "object")
			return xhr.response;

		try {
			return JSON.parse(xhr.responseText);
		} catch (error) {
			return xhr.responseText;
		}
	}

	$.support.fileInput = (function() {
		var input = document.createElement("input");
		input.type = "file";
		return "files" in input && "FormData" in window && "XMLHttpRequest" in window;
	})();

	$.fn.fileupload = function(options) {
		options = $.extend({
			autoUpload: true,
			formData: {},
			type: "POST"
		}, options);

		return this.each(function() {
			var $input = $(this);

			$input.off("change.layoutUpload").on("change.layoutUpload", function(event) {
				var input = this;
				var files = Array.prototype.slice.call(input.files || []);
				var selectionData;
				var started = false;

				if (!files.length)
					return;

				function startUpload() {
					if (started)
						return;
					started = true;

					var loadedByFile = [];
					var totalByFile = [];
					$.each(files, function(index, file) {
						loadedByFile[index] = 0;
						totalByFile[index] = file.size || 0;
					});

					function notifyProgress() {
						var loaded = 0;
						var total = 0;
						$.each(loadedByFile, function(index, value) {
							loaded += value;
							total += totalByFile[index];
						});
						uploadCallback($input, options, "progressall", {
							files: files,
							loaded: loaded,
							total: total || 1
						});
					}

					$.each(files, function(index, file) {
						var fileData = { files: [file], originalFiles: files };
						uploadCallback($input, options, "processdone", fileData);

						var xhr = new XMLHttpRequest();
						var formData = new FormData();
						var extraData = $.isFunction(options.formData) ? options.formData($input.closest("form")) : options.formData;
						appendUploadFormData(formData, extraData);
						formData.append(options.paramName || input.name || "files[]", file, file.name);

						xhr.open(options.type, options.url, true);
						xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
						$.each(options.headers || {}, function(name, value) {
							xhr.setRequestHeader(name, value);
						});

						xhr.upload.addEventListener("progress", function(progressEvent) {
							if (!progressEvent.lengthComputable)
								return;
							loadedByFile[index] = progressEvent.loaded;
							totalByFile[index] = progressEvent.total;
							notifyProgress();
						});

						xhr.addEventListener("load", function() {
							var data = {
								files: [file],
								originalFiles: files,
								result: parseUploadResult(xhr),
								jqXHR: xhr
							};

							if (xhr.status >= 200 && xhr.status < 300) {
								loadedByFile[index] = totalByFile[index];
								notifyProgress();
								uploadCallback($input, options, "done", data);
							} else {
								data.errorThrown = xhr.statusText;
								uploadCallback($input, options, "fail", data);
							}
						});

						xhr.addEventListener("error", function() {
							uploadCallback($input, options, "fail", {
								files: [file],
								originalFiles: files,
								jqXHR: xhr,
								errorThrown: "Network error"
							});
						});

						xhr.send(formData);
					});

					$input.val("");
				}

				selectionData = {
					files: files,
					submit: startUpload
				};

				if (!uploadCallback($input, options, "change", selectionData))
					return;

				if (options.autoUpload !== false)
					startUpload();
			});
		});
	};

	/* Help wizard */
	var helpWizardState = null;

	function helpWizardLocation($step) {
		var options = $step.attr("data-options") || "";
		var match = options.match(/(?:^|;)\s*tipLocation\s*:\s*([^;]+)/i);
		var location = match ? $.trim(match[1]).toLowerCase() : "bottom";

		return $.inArray(location, ["top", "bottom", "left", "right"]) !== -1 ? location : "bottom";
	}

	function helpWizardTarget($step) {
		var className = $.trim($step.attr("data-class") || "");

		if (!className)
			return $(document.body);

		return $("." + $.escapeSelector(className)).filter(":visible").first();
	}

	function positionHelpWizard() {
		if (!helpWizardState || !helpWizardState.$tip || !helpWizardState.$target)
			return;

		var $tip = helpWizardState.$tip;
		var $target = helpWizardState.$target;
		var isBody = $target.is("body");
		var margin = 14;
		var scrollTop = $(window).scrollTop();
		var scrollLeft = $(window).scrollLeft();
		var viewportWidth = $(window).width();
		var viewportHeight = $(window).height();
		var tipWidth = $tip.outerWidth();
		var tipHeight = $tip.outerHeight();
		var $nub = $tip.find(".helpWizard-nub");

		if (isBody) {
			$tip.css({
				top: Math.round(scrollTop + Math.max(10, (viewportHeight - tipHeight) / 2)),
				left: Math.round(scrollLeft + Math.max(10, (viewportWidth - tipWidth) / 2))
			});
			$nub.hide();
			return;
		}

		var targetOffset = $target.offset();
		var targetWidth = $target.outerWidth();
		var targetHeight = $target.outerHeight();
		var locations = {
			top: ["top", "bottom"],
			bottom: ["bottom", "top"],
			left: ["left", "right", "top", "bottom"],
			right: ["right", "left", "top", "bottom"]
		}[helpWizardState.location];
		var coordinates = null;
		var location = locations[0];

		function coordinatesFor(candidate) {
			if (candidate === "top")
				return { top: targetOffset.top - tipHeight - margin, left: targetOffset.left };
			if (candidate === "left")
				return { top: targetOffset.top, left: targetOffset.left - tipWidth - margin };
			if (candidate === "right")
				return { top: targetOffset.top, left: targetOffset.left + targetWidth + margin };

			return { top: targetOffset.top + targetHeight + margin, left: targetOffset.left };
		}

		$.each(locations, function(index, candidate) {
			var candidateCoordinates = coordinatesFor(candidate);
			var fits = candidateCoordinates.top >= scrollTop + 10 &&
				candidateCoordinates.left >= scrollLeft + 10 &&
				candidateCoordinates.top + tipHeight <= scrollTop + viewportHeight - 10 &&
				candidateCoordinates.left + tipWidth <= scrollLeft + viewportWidth - 10;

			if (fits) {
				location = candidate;
				coordinates = candidateCoordinates;
				return false;
			}
		});

		if (!coordinates)
			coordinates = coordinatesFor(location);

		coordinates.left = Math.max(scrollLeft + 10, Math.min(coordinates.left, scrollLeft + viewportWidth - tipWidth - 10));
		coordinates.top = Math.max(scrollTop + 10, Math.min(coordinates.top, scrollTop + viewportHeight - tipHeight - 10));
		$tip.css({ top: Math.round(coordinates.top), left: Math.round(coordinates.left) });

		var nubClass = { top: "bottom", bottom: "top", left: "right", right: "left" }[location];
		var nubPosition;
		$nub.show().attr("class", "helpWizard-nub " + nubClass).css({ top: "", right: "", bottom: "", left: "" });

		if (location === "top" || location === "bottom") {
			nubPosition = targetOffset.left + (targetWidth / 2) - coordinates.left;
			nubPosition = Math.max(14, Math.min(nubPosition, tipWidth - 14));
			$nub.css("left", Math.round(nubPosition));
		} else {
			nubPosition = targetOffset.top + (targetHeight / 2) - coordinates.top;
			nubPosition = Math.max(14, Math.min(nubPosition, tipHeight - 14));
			$nub.css("top", Math.round(nubPosition));
		}
	}

	function closeHelpWizard(completed) {
		if (!helpWizardState)
			return;

		var onComplete = helpWizardState.options.onComplete;
		if (helpWizardState.$tip)
			helpWizardState.$tip.remove();
		$(window).off(".helpWizard");
		$(document).off(".helpWizard");
		helpWizardState = null;

		if (completed && $.isFunction(onComplete))
			onComplete();
	}

	function showHelpWizardStep() {
		var state = helpWizardState;

		if (!state)
			return;

		if (state.$tip)
			state.$tip.remove();

		while (state.index < state.$steps.length) {
			var $step = state.$steps.eq(state.index);
			var $target = helpWizardTarget($step);

			if ($target.length) {
				state.$target = $target;
				state.location = helpWizardLocation($step);
				state.$tip = $(
					"<div class=\"helpWizard-tip-guide\" role=\"dialog\">" +
						"<span class=\"helpWizard-nub\"></span>" +
						"<div class=\"helpWizard-content-wrapper\"></div>" +
						"<button type=\"button\" class=\"helpWizard-next-tip\"></button>" +
						"<button type=\"button\" class=\"helpWizard-close-tip\" aria-label=\"Chiudi\">&times;</button>" +
					"</div>"
				).appendTo(document.body);
				state.$tip.find(".helpWizard-content-wrapper").html($step.html());
				state.$tip.find(".helpWizard-next-tip").text(state.options.nextLabel);
				state.$tip.on("click", ".helpWizard-next-tip", function() {
					state.index++;
					showHelpWizardStep();
				});
				state.$tip.on("click", ".helpWizard-close-tip", function() {
					closeHelpWizard(true);
				});

				state.$tip.css({ display: "block", visibility: "hidden" });
				positionHelpWizard();
				state.$tip.hide().css("visibility", "visible").fadeIn(200);

				if (!$target.is("body")) {
					$("html, body").stop().animate({
						scrollTop: Math.max(0, $target.offset().top - ($(window).height() / 2))
					}, 300, positionHelpWizard);
				}
				return;
			}

			state.index++;
		}

		closeHelpWizard(true);
	}

	window.helpWizard = {
		start: function(selector, options) {
			closeHelpWizard(false);

			var $container = $(selector);
			if (!$container.length)
				return;

			helpWizardState = {
				$steps: $container.children("li"),
				$tip: null,
				$target: null,
				index: 0,
				location: "bottom",
				options: $.extend({ nextLabel: "Successivo", onComplete: null }, options)
			};

			$(window).on("resize.helpWizard scroll.helpWizard", positionHelpWizard);
			$(document).on("keydown.helpWizard", function(event) {
				if (event.which === 27)
					closeHelpWizard(true);
			});
			showHelpWizardStep();
		},
		close: function() {
			closeHelpWizard(true);
		}
	};
})(window.jQuery);
