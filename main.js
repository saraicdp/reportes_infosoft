$(document).ready(function() {
	$(window).scroll(function(){

		var barra = $(window).scrollTop();
		var posicion = barra * 0.100;

		$('body').css({ 'background-position': '0 -' + posicion + 'px' });

	});

	$('#navigator').find('li').each(function() {
		$(this).click(function() {
			var target = $(this).find('a').attr('href').slice(1);
			$('.box-content').each(function() {
				var classes = $(this).attr('class').split(' ');
				var name = $(this).attr('id');
				if(classes.includes('active')) {
					if(target !== name) {
						$(this).attr('class', classes
							     .filter(function(c) { return c !== 'active'})
							     .join(' '));
					}
				} else {
					if(target === name) {
						classes.push('active');
						$(this).attr('class', classes.join(' '));
					}
				}
			});
		});
	});

	$('#inventario > form').submit(function(e) {
		var select = $(this).find('select[name=grupos]');

		if (select.val() === 'ALL') {
			e.preventDefault();
			var options = [];
			select.find('option').each(function() {
				options.push($(this).val());
			});
			
			var selected = options.filter(function(opt) { return opt !== 'ALL'; });
			
			$.ajax({
				url: 'inv_x_gru_ivo.php',
				method: 'POST',
				body: {
					'fdesde': $(this).find('input[name=fdesde]').val(),
					'fhasta': $(this).find('input[name=fhasta]').val(),
					'grupos': selected
				}
			});
		}
	});

});
