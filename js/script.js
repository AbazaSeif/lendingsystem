$(document).ready(function() {
	
	
	$('img').hide();
	$('#mainpanel').hide();
	$('img').fadeIn(1000);
	$('#mainpanel').fadeIn(1500);
	
	
	$('.form_input').hide();


	$('#toggle').click(function() {
		var clicks = $(this).data('clicks');
		if(clicks) {
			$('.form_input').slideUp('slow','swing');
		}
		else {
			
			$('.form_input').slideDown('slow','swing');
			// $('.form_search').slideUp('fast','swing');
			// $('.form_loan').slideUp('fast','swing');
			// $('.form_payment').slideUp('slow','swing');
			$('.form_search').hide();		
			$('.form_loan').hide();
			$('.form_payment').hide();
		}

		$(this).data('clicks', !clicks);
	});

	$('.form_search').hide();

	$('#search_toggle').click(function() {
		var clicks  = $(this).data('clicks');
		
		if(clicks) {
			$('.form_search').slideDown('slow','swing');
			//$('.form_input').slideUp('fast','swing');
			//$('.form_loan').slideUp('fast','swing');
			//$('.form_payment').slideUp('slow','swing');
			$('.form_input').hide();
			$('.form_loan').hide();
			$('.form_payment').hide();
		}
		else {
			$('.form_search').slideUp('slow','swing');
		}
		$(this).data('clicks', !clicks);


		
	});	

	$('.form_loan').hide();

	$('#loan_toggle').click(function() {
		var clicks  = $(this).data('clicks');
		
		if(clicks) {
			$('.form_loan').slideUp('slow','swing');
		}
		else {
			$('.form_loan').slideDown('slow','swing');
			//$('.form_input').slideUp('fast','swing');
			//$('.form_search').slideUp('fast','swing');
			//$('.form_payment').slideUp('slow','swing');
			$('.form_input').hide();
			$('.form_search').hide();		
			$('.form_payment').hide();
		}
		$(this).data('clicks', !clicks);


		
	});

	$('.form_payment').hide();

	$('#payment_toggle').click(function() {
		var clicks = $(this).data('clicks');

		if(clicks) {
			$('.form_payment').slideUp('slow','swing');
		}
		else {
			$('.form_payment').slideDown('slow','swing');
			//$('.form_input').slideUp('fast','swing');
			//$('.form_search').slideUp('fast','swing');
			//$('.form_loan').slideUp('fast','swing');
			$('.form_input').hide();
			$('.form_search').hide();		
			$('.form_loan').hide();
		}
		$(this).data('clicks', !clicks);	
	});


	$('.settingstoggle').popover();
	$('.settingstoggle').focusout(function() {
		$(this).popover('hide');
	});

	/*$('.settingstoggle').click(function() {
		$(this).popover('show');
	});
	$('.settingstoggle').focusout(function() {
		$(this).popover('hide');
	});
	*/

	$('#myTab a').click(function (e) {
  	e.preventDefault();
  	$(this).tab('show');
	})

	$('#inbox a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#client_tab a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

});