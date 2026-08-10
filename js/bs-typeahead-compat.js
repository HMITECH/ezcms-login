/*
 * Minimal Bootstrap-2 style $.fn.typeahead shim for ezCMS.
 *
 * Bootstrap 3+ dropped the typeahead plugin. ezCMS uses it only for the
 * left-tree page search (#txtsearch) with a {source, highlighter, updater}
 * config. This reimplements just that API on top of a BS5 .dropdown-menu so
 * the existing call site keeps working without pulling a new dependency.
 */
(function ($) {
	"use strict";
	if ($.fn.typeahead) return; // never clobber a real plugin (e.g. filemanager's)

	$.fn.typeahead = function (opts) {
		opts = opts || {};
		var LIMIT = opts.items || 8;

		return this.each(function () {
			var $input = $(this).attr('autocomplete', 'off');
			if ($input.parent().css('position') === 'static') $input.parent().css('position', 'relative');

			var $menu = $('<ul class="typeahead dropdown-menu" style="display:none;max-height:280px;overflow:auto"></ul>');
			$input.after($menu);

			function hide() { $menu.hide(); }

			function select(item) {
				hide();
				if (opts.updater) opts.updater.call($input[0], item);
			}

			function render(items) {
				$menu.empty();
				if (!items.length) { hide(); return; }
				items.slice(0, LIMIT).forEach(function (item) {
					var label = opts.highlighter ? opts.highlighter.call($input[0], item) : String(item);
					$('<li><a href="#" class="dropdown-item text-truncate">' + label + '</a></li>')
						.data('ta-item', item)
						.on('mousedown', function (e) { e.preventDefault(); select($(this).data('ta-item')); })
						.appendTo($menu);
				});
				var pos = $input.position();
				$menu.css({
					position: 'absolute',
					top: pos.top + $input.outerHeight(),
					left: pos.left,
					minWidth: $input.outerWidth(),
					zIndex: 2000
				}).show();
			}

			function lookup() {
				var q = $.trim($input.val());
				if (!q) { hide(); return; }
				var items = opts.source ? opts.source.call($input[0], null, q) : [];
				if (!Array.isArray(items)) items = [];
				var ql = q.toLowerCase();
				render(items.filter(function (it) { return String(it).toLowerCase().indexOf(ql) !== -1; }));
			}

			$input.on('input', lookup)
				.on('keydown', function (e) { if (e.keyCode === 27) hide(); });
			$(document).on('click', function (e) {
				if (e.target !== $input[0] && !$menu[0].contains(e.target)) hide();
			});
		});
	};
})(jQuery);
