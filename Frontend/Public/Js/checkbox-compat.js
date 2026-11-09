/* Native checkbox/radio styling with a small compatibility surface for old calls. */
(function ($, document) {
	'use strict';
	if (!$) return;

	var defaults = { checkboxClass: 'icheckbox_minimal', radioClass: 'iradio_minimal' };
	var dataKey = 'checkboxCompat';

	function dispatchChange(input) {
		var event;
		if (typeof Event === 'function') event = new Event('change', { bubbles: true });
		else {
			event = document.createEvent('HTMLEvents');
			event.initEvent('change', true, false);
		}
		input.dispatchEvent(event);
	}

	function initialize(input, options) {
		if (!/^(checkbox|radio)$/i.test(input.type) || $.data(input, dataKey)) return;
		var isRadio = input.type.toLowerCase() === 'radio';
		var className = isRadio ? options.radioClass : options.checkboxClass;
		var classes = String(className || '').split(/\s+/).filter(Boolean);
		$.data(input, dataKey, { originalClass: input.getAttribute('class') });
		$(input).addClass(classes.join(' '))
			.on('change.checkboxCompat', function () {
				$(this).trigger('ifToggled').trigger('ifChanged')
					.trigger(this.checked ? 'ifChecked' : 'ifUnchecked');
			})
			.on('click.checkboxCompat', function () { $(this).trigger('ifClicked'); });
		$(input).trigger('ifCreated');
	}

	$.fn.iCheck = function (command, fire) {
		if (typeof command !== 'string') {
			var options = $.extend({}, defaults, command || {});
			return this.each(function () { initialize(this, options); });
		}

		var action = command.toLowerCase();
		return this.each(function () {
			var input = this, data = $.data(input, dataKey);
			if (action === 'destroy') {
				if (data && data.originalClass === null) $(input).removeAttr('class');
				else if (data) $(input).attr('class', data.originalClass);
				$(input).off('.checkboxCompat').trigger('ifDestroyed');
				$.removeData(input, dataKey);
				return;
			}
			if (action === 'enable' || action === 'disable') {
				input.disabled = action === 'disable';
				$(input).trigger(action === 'disable' ? 'ifDisabled' : 'ifEnabled');
				return;
			}
			if (action === 'indeterminate' || action === 'determinate') {
				input.indeterminate = action === 'indeterminate';
				$(input).trigger(action === 'indeterminate' ? 'ifIndeterminate' : 'ifDeterminate');
				return;
			}
			if (action === 'update') return;
			if (action !== 'check' && action !== 'uncheck' && action !== 'toggle') return;
			if (input.disabled) return;
			var checked = action === 'check' ? true : action === 'uncheck' ? false : !input.checked;
			if (input.checked === checked) return;
			input.checked = checked;
			if (!fire) dispatchChange(input);
		});
	};
})(window.jQuery, document);
