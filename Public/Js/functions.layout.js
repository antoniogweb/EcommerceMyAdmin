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
})(window.jQuery);
