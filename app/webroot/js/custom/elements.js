jQuery(document).ready(function(){

	
	//color picker
	jQuery('#colorpicker').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			jQuery(el).val('#'+hex);
			jQuery(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			jQuery(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		jQuery(this).ColorPickerSetColor(this.value);
	});
	
	
	//Colorpicker 2
	jQuery('#colorSelector').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('#colorSelector span').css('backgroundColor', '#' + hex);
			jQuery('#colorpicker2').val('#'+hex);
		}
	});
	
	
	//Colorpicker Flat Mode
	jQuery('#colorpickerholder').ColorPicker({
		flat: true,
		onChange: function (hsb, hex, rgb) {
			jQuery('#colorpicker3').val('#'+hex);
		}
	});
	
	
	/**
	 * Slider 
	**/
	jQuery("#slider").slider({value: 40});
	
	//Slider that snap to increments
	jQuery("#slider2").slider({
			value:100,
			min: 0,
			max: 500,
			step: 50,
			slide: function(event, ui) {
				jQuery("#amount").text("$"+ui.value);
			}
	});
	jQuery("#amount").text("$" + jQuery("#slider").slider("value"));

	
	//Slider with range
	jQuery("#slider3").slider({
		range: true,
		min: 0,
		max: 500,
		values: [ 75, 300 ],
		slide: function( event, ui ) {
			jQuery("#amount2").text("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
		}
	});
	jQuery("#amount2").text("$" + jQuery("#slider3").slider("values", 0) +
			" - $" + jQuery("#slider3").slider("values", 1));
	
	// Slider with fixed minimum
	jQuery("#slider4").slider({
			range: "min",
			value: 37,
			min: 1,
			max: 100,
			slide: function( event, ui ) {
				jQuery("#amount4").text("$" + ui.value);
			}
	});
	jQuery("#amount4").text("$"+jQuery("#slider4").slider("value"));

	//Slider with fixed maximum
	jQuery("#slider5").slider({
			range: "max",
			value: 60,
			min: 1,
			max: 100,
			slide: function(event, ui) {
				jQuery("#amount5").text("$"+ui.value);
			}
	});
	jQuery("#amount5").text("$"+jQuery("#slider5").slider("value"));
	
	//Slider vertical
	jQuery("#slider6").slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 60,
			slide: function( event, ui ) {
				jQuery("#amount6").text(ui.value);
			}
	});
	jQuery("#amount6").text( jQuery("#slider6").slider("value"));

	
	//Slider vertical with range
	jQuery("#slider7").slider({
			orientation: "vertical",
			range: true,
			values: [17, 67],
			slide: function(event, ui) {
				jQuery("#amount7").text("$"+ui.values[0]+"-$"+ui.values[1]);
			}
		});
	jQuery("#amount7").text("$"+jQuery("#slider7").slider("values",0) +
			" - $"+jQuery("#slider7").slider("values",1));
	
	
	/**
	 * Growl Notification
	**/
	jQuery('.growl').click(function(){
		jQuery.jGrowl("Hello world!");
		return false;
	});
	
	jQuery('.growl2').click(function(){
		var msg = "This notification will live a little longer.";
		var position = "top-right";
		var scrollpos = jQuery(document).scrollTop();
		if(scrollpos < 50) position = "customtop-right";
		jQuery.jGrowl(msg, { life: 5000, position: position});
		return false;
	});
	
	//this will prevent growl box to show on top of the header when
	//scroll event is fired
	jQuery(document).scroll(function(){
		if(jQuery('.jGrowl').length != 0) {
			var pos = jQuery(document).scrollTop();
			if(pos < 50) jQuery('.jGrowl').css({top: '70px'}); else jQuery('.jGrowl').css({top: '0'});
		}
	});
	
	//button animate upon hover
	jQuery('.anchorbutton').hover(function(){
		jQuery(this).stop().animate({
			backgroundColor: '#222', 
			borderColor: '#111', 
			color: '#fff'
		},500);
		jQuery(this).find('span').stop().animate({
			backgroundColor: '#333', 
			borderColor: '#444'
		},500);
	},function(){
		jQuery(this).stop().animate({
			backgroundColor: '#f7f7f7',
			borderColor: '#ccc',
			color: '#333'
		},300);
		jQuery(this).find('span').stop().animate({
			backgroundColor: '#fff', 
			borderColor: '#ddd'
		},300);
	});
	
	/**
	 * Modal Alert Boxes
	**/
	jQuery('.alertboxbutton').click(function(){
		jAlert('This is a custom alert box', 'Alert Dialog');
		return false;
	});
	
	jQuery('.confirmbutton').click(function(){
		jConfirm('Can you confirm this?', 'Confirmation Dialog', function(r) {
			jAlert('Confirmed: ' + r, 'Confirmation Results');
		});
		return false;
	});
	
	jQuery('.promptbutton').click(function(){
		jPrompt('Type something:', 'Prefilled value', 'Prompt Dialog', function(r) {
			if( r ) alert('You entered ' + r);
		});
		return false;
	});
	
	jQuery('.alerthtmlbutton').click(function(){
		jAlert('You can use HTML, such as <strong>bold</strong>, <em>italics</em>, and <u>underline</u>!');
		return false;
	});
	
	
	/*** pagination ***/
	jQuery('.pagination a').click(function(){
		var p = jQuery(this).parent();
		if(!p.hasClass('previous') && !p.hasClass('first') && !p.hasClass('next') && !p.hasClass('last')) {
			jQuery('.pagination a').each(function(){
				jQuery(this).removeClass('current');
			});
			jQuery(this).addClass('current');
			
			//disable next and last button when active page is the last page
			if(jQuery(this).parent().next().hasClass('next')) {
				jQuery('.pagination .next a, .pagination .last a').addClass('disable');	
			} else {
				jQuery('.pagination .next a, .pagination .last a').removeClass('disable');	
			}
			
			//disable first and previous button when active page is the first page
			if(jQuery(this).parent().prev().hasClass('previous')) {
				jQuery('.pagination .previous a, .pagination .first a').addClass('disable');	
			} else {
				jQuery('.pagination .previous a, .pagination .first a').removeClass('disable');	
			}
			
		}
		return false;
	});
	
	//clicking next button
	jQuery('.pagination li.next a').click(function(){
		if(!jQuery(this).hasClass('disable')) {
			if(!jQuery(this).parent().prev().find('a').hasClass('current')) {
				jQuery('.pagination a.current').removeClass('current').parent().next().find('a').addClass('current');
			}
		}
		if(jQuery('.pagination a.current').parent().next().hasClass('next')) {
			jQuery('.pagination .next a, .pagination .last a').addClass('disable');	
		}
		if(!jQuery('.pagination a.current').parent().prev().hasClass('previous')) {
			jQuery('.pagination .previous a, .pagination .first a').removeClass('disable');	
		}

	});
	
	
	//clicking previous button
	jQuery('.pagination li.previous a').click(function(){
		if(!jQuery(this).hasClass('disable')) {
			if(!jQuery(this).parent().next().find('a').hasClass('current')) {
				jQuery('.pagination a.current').removeClass('current').parent().prev().find('a').addClass('current');
			}
		}
		if(jQuery('.pagination a.current').parent().prev().hasClass('previous')) {
			jQuery('.pagination .first a, .pagination .previous a').addClass('disable');	
		}
		if(!jQuery('.pagination a.current').parent().next().hasClass('next')) {
			jQuery('.pagination .next a, .pagination .last a').removeClass('disable');	
		}

	});
	
	//clicking last button
	jQuery('.pagination .last a').click(function(){
		jQuery(this).addClass('disable');
		jQuery('.pagination .next a').addClass('disable');
		jQuery('.pagination .current').removeClass('current');
		jQuery('.pagination .next a').parent().prev().find('a').addClass('current');
		jQuery('.pagination .first a, .pagination .previous a').removeClass('disable');
	});
	
	//clicking last button
	jQuery('.pagination .first a').click(function(){
		jQuery(this).addClass('disable');
		jQuery('.pagination .previous a').addClass('disable');
		jQuery('.pagination .current').removeClass('current');
		jQuery('.pagination .previous a').parent().next().find('a').addClass('current');
		jQuery('.pagination .last a, .pagination .next a').removeClass('disable');
	});
	
	
	//show tabbed widget
	jQuery('#tabs').tabs();
	
	//datepicker
	jQuery( "#datepicker" ).datepicker();


});