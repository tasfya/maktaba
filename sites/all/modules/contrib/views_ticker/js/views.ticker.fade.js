(function ($) {

	$.fn.newsTickerFade = function (b) {
		b = b || 4000;
		initTicker = function (a) {
			stopTicker(a);
			a.items = $("li", a);
			a.items.not(":eq(0)").hide().end();
			a.currentitem = 0;
			startTicker(a)
		};
		startTicker = function (a) {
			a.tickfn = setInterval(function () {
				doTick(a)
			},
			b)
		};
		stopTicker = function (a) {
			clearInterval(a.tickfn)
		};
		pauseTicker = function (a) {
			a.pause = true
		};
		resumeTicker = function (a) {
			a.pause = false
		};
		doTick = function (a) {
			if (a.pause) return;
			a.pause = true;
			$(a.items[a.currentitem]).fadeOut("slow", function () {
				$(this).hide();
				a.currentitem = ++a.currentitem % (a.items.size());
				$(a.items[a.currentitem]).fadeIn("slow", function () {
					a.pause = false
				})
			})
		};
		this.each(function () {
			if (this.nodeName.toLowerCase() != "ul") return;
			initTicker(this)
		}).addClass("newsticker").hover(function () {
			pauseTicker(this)
		},
		function () {
			resumeTicker(this)
		});
		return this
	}

})(jQuery);
