$(document).ready (function() {
	$(window).scroll(function(){

		var barra= $(window).scrolltop();
		var posicion= barra * 0.100;

		$('body'.css({
			'background-position': '0 -' + posicion + 'px'
		});

	});


});
