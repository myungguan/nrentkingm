$(function() {
	/**
	 * header
	 */
	(function() {
		var $header = $('header');
		$(document)
			.on('mouseenter focus', 'header ul.menu a', function() {
				$header.addClass('on');
			})
			.on('mouseleave', 'header', function() {
				$header.removeClass('on');
			})
			.on('blur', 'header a', function() {
				$header.removeClass('on');
			});
	})();

	/**
	 * scroll wrap
	 */
	(function() {
		var calculateScrollTop = function(innerHeight, contentHeight, scrollBarHeight, scrollTop) {
			var maxScrollTop = contentHeight - innerHeight;
			var maxVirtualScrolltop = innerHeight - scrollBarHeight;

			return scrollTop * maxVirtualScrolltop / maxScrollTop;
		};
		var calculateScrollTopReverse = function(innerHeight, contentHeight, scrollBarHeight, scrollTop) {
			var maxScrollTop = contentHeight - innerHeight;
			var maxVirtualScrolltop = innerHeight - scrollBarHeight;

			return scrollTop * maxScrollTop / maxVirtualScrolltop;
		};

		var scrolling = null;
		$(document)
			.on('init', '.scrollWrap', function(e) {
				e.stopPropagation();

				var $scrollWrap = $(this);

				if(!$scrollWrap.data('init')) {
					$scrollWrap.data('init', true);
					var $scrollWrapInner = $scrollWrap.find('.scrollWrapInner');
					var $scrollWrapBar = $('<span class="scrollWrapBar"></span>');

					$scrollWrapInner.data('scrollWrap', $scrollWrap);
					$scrollWrapInner.data('scrollWrapBar', $scrollWrapBar);
					$scrollWrap.append($scrollWrapBar);
					$scrollWrap.trigger('resizeScrollBar');
				}
			})
			.on('resizeScrollBar', '.scrollWrap', function(e) {
				e.stopPropagation();

				var $scrollWrap = $(this);
				var $scrollWrapInner = $scrollWrap.find('.scrollWrapInner');
				var $scrollWrapContent = $scrollWrap.find('.scrollWrapContent');
				var $scrollWrapBar = $scrollWrap.find('.scrollWrapBar');

				setTimeout(function() {
					var innerHeight = $scrollWrapInner.outerHeight();
					var contentHeight = $scrollWrapContent.outerHeight();
					var scrollBarHeight = innerHeight * innerHeight / contentHeight;
					if(scrollBarHeight < 50)
						scrollBarHeight = 50;

					$scrollWrapBar.data('scrollWrap', $scrollWrap);
					$scrollWrapBar.data('scrollWrapInner', $scrollWrapInner);
					$scrollWrapBar.data('innerHeight', innerHeight);
					$scrollWrapBar.data('contentHeight', contentHeight);
					$scrollWrapBar.data('scrollBarHeight', scrollBarHeight);
					$scrollWrapBar.data('top', $scrollWrapInner.scrollTop());

					if(innerHeight >= contentHeight) {
						$scrollWrap.addClass('noscroll');
					} else {
						$scrollWrap.removeClass('noscroll');
						var top = calculateScrollTop(innerHeight, contentHeight, scrollBarHeight, $scrollWrapInner.scrollTop());
						$scrollWrapBar.data('top', top).css({height: scrollBarHeight, top: top});
					}
				}, 100);

			})
			.on('mousedown', '.scrollWrapBar', function(e) {
				e.stopPropagation();

				var $this = $(this);
				scrolling = {
					$scrollBar: $this,
					$wrap: $this.data('scrollWrap'),
					$inner: $this.data('scrollWrapInner'),
					max: $this.data('innerHeight') - $this.data('scrollBarHeight'),
					innerHeight: $this.data('innerHeight'),
					contentHeight: $this.data('contentHeight'),
					scrollBarHeight: $this.data('scrollBarHeight'),
					from: {
						x: e.pageX,
						y: e.pageY,
						top: $this.data('top')
					}
				};
				scrolling.$wrap.addClass('on');
			})
			.on('mousemove', function(e) {
				if(scrolling) {
					e.preventDefault();
					e.stopPropagation();
					var $scrollBar = scrolling.$scrollBar;
					var y = e.pageY - scrolling.from.y;
					var top = scrolling.from.top + y;
					if(top < 0) top = 0;
					if(top > scrolling.max) top = scrolling.max;

					$scrollBar.css({top: top});
					scrolling.$inner.scrollTop(calculateScrollTopReverse(scrolling.innerHeight, scrolling.contentHeight, scrolling.scrollBarHeight, top));
					return false;
				}
			})
			.on('mouseup', function(e) {
				if(scrolling) {
					scrolling.$wrap.removeClass('on');
				}
				scrolling = null;
			})
		;

		$(window).on('resize', function(e) {
			$('.scrollWrap').trigger('resizeScrollBar');
		});
		$('.scrollWrapInner').on('scroll', function(e) {
			var $scrollWrapInner = $(this);
			var $scrollWrapBar = $scrollWrapInner.data('scrollWrapBar');
			var innerHeight = $scrollWrapBar.data('innerHeight');
			var contentHeight = $scrollWrapBar.data('contentHeight');
			var scrollBarHeight = $scrollWrapBar.data('scrollBarHeight');
			var scrollTop = $scrollWrapInner.scrollTop();

			var top = calculateScrollTop(innerHeight, contentHeight, scrollBarHeight, scrollTop);
			$scrollWrapBar.data('top', top).css({top: top});
		});

		$('.scrollWrap').trigger('init');
	})();
});
