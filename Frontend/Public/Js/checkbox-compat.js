/* Compatibility layer for the legacy iCheck calls used by the frontend. */
(function ($, window, document) {
	'use strict';

	if (!$ || $.fn.iCheck) return;

	var defaults = {
		checkboxClass: 'icheckbox',
		radioClass: 'iradio',
		checkedClass: 'checked',
		disabledClass: 'disabled',
		indeterminateClass: 'indeterminate',
		hoverClass: 'hover',
		focusClass: 'focus',
		activeClass: 'active',
		increaseArea: '0%'
	};
	var dataKey = 'checkboxCompat';

	function classSelector(name) {
		return '.' + String(name || '').trim().split(/\s+/).filter(Boolean).join('.');
	}

	function ensureCss(options) {
		var style = document.getElementById('checkbox-compat-generated-css');
		if (!style) {
			style = document.createElement('style');
			style.id = 'checkbox-compat-generated-css';
			style._checkboxCompatRules = {};
			document.head.appendChild(style);
		}
		var ruleKey = options.checkboxClass + '|' + options.radioClass;
		if (style._checkboxCompatRules[ruleKey]) return;
		var area = parseFloat(options.increaseArea) || 0;
		var extension = Math.max(-50, Math.min(100, area));
		var offset = (-extension) + '%';
		var size = (100 + extension * 2) + '%';
		var inputCss = '{position:absolute!important;top:' + offset + '!important;left:' + offset + '!important;width:' + size + '!important;height:' + size + '!important;margin:0!important;padding:0!important;opacity:0!important;z-index:1!important;cursor:inherit!important}';
		var baseCss = '{position:relative;display:inline-block;vertical-align:middle;cursor:pointer}';
		style._checkboxCompatRules[ruleKey] = true;
		style.textContent += classSelector(options.checkboxClass) + ',' + classSelector(options.radioClass) + baseCss +
			classSelector(options.checkboxClass) + ' input,' + classSelector(options.radioClass) + ' input' + inputCss +
			classSelector(options.checkboxClass) + ' input:disabled,' + classSelector(options.radioClass) + ' input:disabled{cursor:default!important}';
	}

	function state(input) {
		var $input = $(input), data = $input.data(dataKey);
		if (!data) return;
		var $box = data.box;
		$box.toggleClass(data.options.checkedClass, input.checked)
			.toggleClass(data.options.disabledClass, input.disabled)
			.toggleClass(data.options.indeterminateClass, input.indeterminate)
			.toggleClass(data.options.focusClass, document.activeElement === input);
		$input.attr('aria-checked', input.indeterminate ? 'mixed' : String(input.checked));
	}

	function syncRadioGroup(input) {
		if (input.type.toLowerCase() !== 'radio' || !input.name || !input.checked) return;
		$('input[type="radio"]').filter(function () {
			return this !== input && this.name === input.name && this.form === input.form;
		}).each(function () { state(this); });
	}

	function emit(input, name) {
		$(input).trigger(name);
	}

	function emitStateEvents(input) {
		emit(input, 'ifToggled');
		emit(input, 'ifChanged');
		emit(input, input.checked ? 'ifChecked' : 'ifUnchecked');
	}

	function wrap(input, options) {
		var $input = $(input);
		if (!/^(checkbox|radio)$/i.test(input.type) || $input.data(dataKey)) return;
		var isRadio = input.type.toLowerCase() === 'radio';
		var $box = $('<div/>', { 'class': isRadio ? options.radioClass : options.checkboxClass });
		$input.wrap($box);
		$box = $input.parent();
		$input.data(dataKey, { box: $box, options: options, originalStyle: input.getAttribute('style') });
		$input.attr('aria-checked', input.indeterminate ? 'mixed' : String(input.checked));
		$input.on('change.checkboxCompat', function () {
			state(this);
			syncRadioGroup(this);
			emitStateEvents(this);
		});
		$input.on('click.checkboxCompat', function () { emit(this, 'ifClicked'); });
		$input.on('focus.checkboxCompat', function () { state(this); });
		$input.on('blur.checkboxCompat', function () { state(this); });
		$box.on('mouseenter.checkboxCompat', function () { $box.addClass(options.hoverClass); })
			.on('mouseleave.checkboxCompat', function () { $box.removeClass(options.hoverClass + ' ' + options.activeClass); })
			.on('mousedown.checkboxCompat', function () { if (!input.disabled) $box.addClass(options.activeClass); });
		state(input);
		emit(input, 'ifCreated');
	}

	$.fn.iCheck = function (command, fire) {
		if (typeof command === 'string') {
			var action = command.toLowerCase();
			return this.each(function () {
				var input = this, $input = $(input), data = $input.data(dataKey);
				if (action === 'destroy') {
					if (!data) return;
					$input.off('.checkboxCompat').removeData(dataKey).removeAttr('aria-checked');
					data.box.before($input);
					data.box.remove();
					if (data.originalStyle === null) $input.removeAttr('style');
					else $input.attr('style', data.originalStyle);
					emit(input, 'ifDestroyed');
					return;
				}
				if (!data) return;
				var changed = false;
				if (action === 'update') { state(input); return; }
				if (action === 'enable' || action === 'disable') {
					input.disabled = action === 'disable';
					state(input);
					emit(input, action === 'disable' ? 'ifDisabled' : 'ifEnabled');
					return;
				}
				if (action === 'check' || action === 'uncheck' || action === 'toggle') {
					var next = action === 'check' ? true : action === 'uncheck' ? false : !input.checked;
					if (input.disabled || input.checked === next) return;
					if (input.type.toLowerCase() === 'radio' && next) {
						$('input[type="radio"]').filter(function () {
							return this !== input && this.name === input.name && this.form === input.form && this.checked;
						}).each(function () { this.checked = false; state(this); });
					}
					input.checked = next;
					changed = true;
				} else if (action === 'indeterminate' || action === 'determinate') {
					input.indeterminate = action === 'indeterminate';
					state(input);
					if (!fire) emit(input, action === 'indeterminate' ? 'ifIndeterminate' : 'ifDeterminate');
					return;
				} else return;
				state(input);
				if (changed && !fire) {
					emitStateEvents(input);
				}
			});
		}

		var options = $.extend({}, defaults, command || {});
		ensureCss(options);
		return this.each(function () { wrap(this, options); });
	};
})(window.jQuery, window, document);
