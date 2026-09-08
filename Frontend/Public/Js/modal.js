(function (window, document) {
	"use strict";

	var activeModal;
	var previousActiveElement;
	var previousBodyOverflow;

	function closeModal() {
		var modal = activeModal;
		var onClose;

		if (!modal) {
			return;
		}

		activeModal = null;
		onClose = modal.onClose;
		document.body.style.overflow = previousBodyOverflow;
		modal.element.parentNode.removeChild(modal.element);
		document.removeEventListener("keydown", modal.onKeyDown);

		if (previousActiveElement && typeof previousActiveElement.focus === "function") {
			previousActiveElement.focus();
		}

		if (typeof onClose === "function") {
			onClose();
		}
	}

	function modalOpen(options) {
		var overlay;
		var dialog;
		var closeButton;
		var iframe;
		var onKeyDown;

		options = options || {};

		if (!options.url) {
			return;
		}

		closeModal();
		previousActiveElement = document.activeElement;
		previousBodyOverflow = document.body.style.overflow;

		overlay = document.createElement("div");
		overlay.className = "modalOverlay";
		overlay.setAttribute("role", "presentation");

		dialog = document.createElement("div");
		dialog.className = "modalDialog";
		dialog.setAttribute("role", "dialog");
		dialog.setAttribute("aria-modal", "true");
		dialog.setAttribute("aria-label", options.title || "Finestra di modifica");
		dialog.style.width = options.width || "95vw";
		dialog.style.height = options.height || "95vh";

		closeButton = document.createElement("button");
		closeButton.className = "modalClose";
		closeButton.type = "button";
		closeButton.setAttribute("aria-label", "Chiudi");
		closeButton.innerHTML = "&times;";
		closeButton.addEventListener("click", closeModal);

		iframe = document.createElement("iframe");
		iframe.className = "modalFrame";
		iframe.src = options.url;
		iframe.setAttribute("title", options.title || "Finestra di modifica");

		dialog.appendChild(closeButton);
		dialog.appendChild(iframe);
		overlay.appendChild(dialog);
		document.body.appendChild(overlay);
		document.body.style.overflow = "hidden";

		overlay.addEventListener("click", function (event) {
			if (event.target === overlay) {
				closeModal();
			}
		});

		onKeyDown = function (event) {
			if (event.key === "Escape" || event.keyCode === 27) {
				closeModal();
			}
		};
		document.addEventListener("keydown", onKeyDown);
		activeModal = {
			element: overlay,
			onClose: options.onClose,
			onKeyDown: onKeyDown
		};
		closeButton.focus();
	}

	window.modalOpen = modalOpen;
	window.modalClose = closeModal;
}(window, document));
