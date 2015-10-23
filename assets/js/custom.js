$(function() {

	// is debug toggled ?
	if($.cookie('debug') == 1) {
		$('.debug').show();
	}

	// toggle debug
	$('a[href="#toggleDebug"]').on('click', function(e) {
		e.preventDefault();

		if($.cookie('debug') == 0) {
			$.cookie('debug', 1);
			$('.debug').hide();
		} else {
			$.cookie('debug', 0);
			$('.debug').show();
		}

		$('.debug').toggle();
	});

});
