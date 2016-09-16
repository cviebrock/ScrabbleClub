
// array.has() prototype

Array.prototype.has=function(v) {
	for (i=0;i<this.length;i++) {
		if (this[i]==v) return i;
	}
	return undefined;
}


// our own sorting methods for tablesorter

if ($.tablesorter) {

	$.tablesorter.addParser({
		id: 'sc_date',
		is: function(s) { return false; },
		format: function(s) {
			var n = Date.parse(s);
			if (isNaN(n)) return 0;
			return n;
		},
		type: 'numeric'
	});


	$.tablesorter.addParser({
		id: 'sc_record',
		is: function(s) { return false; },
		format: function(s) {
			var p = s.split('-');
			if (p.length==2) {
				var n = parseFloat(p[0]);
				var d = parseFloat(p[1]);
				if (n+d == 0)  return -1;
				return n/(n+d);
			}
			return -1;
		},
		type: 'numeric'
	});

}

$('[rel="tooltip"]').tooltip();


if ($.fancybox) {

	$('.fancybox').fancybox({
		// nextEffect	: 'fade',
		// prevEffect	: 'fade',
		helpers : {
			title : {
				type: 'inside'
			}
		},
		beforeLoad: function() {
			this.title = $(this.element).data('original-title');
		}
	});

}
