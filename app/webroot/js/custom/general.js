jQuery.noConflict();

jQuery(document).ready(function(){
	
	//search box of header
	jQuery('#keyword').bind('focusin focusout', function(e){
		var t = jQuery(this);
		if(e.type == 'focusin' && t.val() == 'Search here') {
			t.val('');
		} else if(e.type == 'focusout' && t.val() == '') {
			t.val('Search here');	
		}
	});
	
	
	//userinfo
	jQuery('.userinfo').click(function(){
		if(!jQuery(this).hasClass('userinfodrop')) {
			var t = jQuery(this);
			jQuery('.userdrop').width(t.width() + 30);	//make this width the same with the user info wrapper
			jQuery('.userdrop').slideDown('fast');
			t.addClass('userinfodrop');					//add class to change color and background
			
		} else {
			jQuery(this).removeClass('userinfodrop');
			jQuery('.userdrop').hide();
		}
		
		//remove notification box if visible
		jQuery('.notialert').removeClass('notiactive');
		jQuery('.notibox').hide();
			
		return false;
	});
	
	
	//notification onclick
	jQuery('.notialert').click(function(){
		var t = jQuery(this);
		var url = t.attr('href');
		if(!t.hasClass('notiactive')) {
			jQuery('.notibox').slideDown('fast');			//show notification box
			jQuery('.noticontent').empty();					//clear data
			jQuery('.notibox .tabmenu li').each(function(){ 
				jQuery(this).removeClass('current');		//reset active tab menu
			});
			//make first li as default active menu
			jQuery('.notibox .tabmenu li:first-child').addClass('current');
			
			t.addClass('notiactive');
			
			jQuery('.notibox .loader').show();				//show loading image while waiting a response from server
			jQuery.post(url,function(data){
				jQuery('.notibox .loader').hide();			//hide loader after response		 
				jQuery('.noticontent').append(data);		//append data from server to .noticontent box
			});
		} else {
			t.removeClass('notiactive');
			jQuery('.notibox').hide();
		}
		
		//this will hide user info drop down when visible
		jQuery('.userinfo').removeClass('userinfodrop');
		jQuery('.userdrop').hide();
		
		return false;
	});
	
	
	jQuery(document).click(function(event) {
		var ud = jQuery('.userdrop');
		var nb = jQuery('.notibox');
		
		//hide user drop menu when clicked outside of this element
		if(!jQuery(event.target).is('.userdrop') && ud.is(':visible')) {
			ud.hide();
			jQuery('.userinfo').removeClass('userinfodrop');
		}
		
		//hide notification box when clicked outside of this element
		if(!jQuery(event.target).is('.notibox') && nb.is(':visible')) {
			nb.hide();
			jQuery('.notialert').removeClass('notiactive');
		}
	});
	
	
	//notification box tab menu
	jQuery('.tabmenu a').click(function(){
		var url = jQuery(this).attr('href');
		
		//reset active menu
		jQuery('.tabmenu li').each(function(){
			jQuery(this).removeClass('current');
		});
		
		jQuery('.noticontent').empty();					//empty content first to display new data
		jQuery('.notibox .loader').show();
		jQuery(this).parent().addClass('current');		//add current class to menu
		jQuery.post(url,function(data){
			jQuery('.notibox .loader').hide();			
			jQuery('.noticontent').append(data);		//inject new data from server
		});
		return false;
	});
	
	
	// Widget Box Title on Hover event
	// show arrow image in the right side of the title upon hover
	jQuery('.widgetbox .title').hover(function(){
		if(!jQuery(this).parent().hasClass('uncollapsible'))									   
			jQuery(this).addClass('titlehover');
	}, function(){
		jQuery(this).removeClass('titlehover');
	});
	
	//show/hide widget content when widget title is clicked
	jQuery('.widgetbox .title').click(function(){
		if(!jQuery(this).parent().hasClass('uncollapsible')) {									   
			if(jQuery(this).next().is(':visible')) {
				jQuery(this).next().slideUp('fast');
				jQuery(this).addClass('widgettoggle');
			} else {
				jQuery(this).next().slideDown('fast');
				jQuery(this).removeClass('widgettoggle');
			}
		}
	});
	
	//wrap menu to em when click will return to true
	//this code is required in order the code (next below this code) to work.
	jQuery('.leftmenu a span').each(function(){
		jQuery(this).wrapInner('<em />');
	});

	jQuery('.leftmenu a').click(function(e) {
										 
		var t = jQuery(this);								 
		var p = t.parent();
		var ul = p.find('ul');
		var li = jQuery(this).parents('.lefticon');
		
		//check if menu have sub menu
		if(jQuery(this).hasClass('menudrop')) {
			
			//check if menu is collapsed
			if(!li.length > 0) {
				
				//check if sub menu is available
				if(ul.length > 0) {
					
					//check if menu is visible
					if(ul.is(':visible')) {
						ul.slideUp('fast');
						p.next().css({borderTop: '0'});
						t.removeClass('active');
					} else {
						ul.slideDown('fast');	
						p.next().css({borderTop: '1px solid #ddd'});
						t.addClass('active');
					}
				}
	
				if(jQuery(e.target).is('em'))
					return true;
				else
					return false;
			} else {
				return true;	
			}
		
		//redirect to assigned link when menu does not have a sub menu
		} else {
			return true;
		}
	});
	
	//show tooltip menu when left menu is collapsed
	jQuery('.leftmenu > ul > li').hover(function(){
		if(jQuery(this).parents('.lefticon').length > 0) {
			jQuery(this).find('.menutip').stop(true,true).fadeIn();
		}
	},function(){
		if(jQuery(this).parents('.lefticon').length > 0) {
			jQuery(this).find('.menutip').stop(true,true).fadeOut();
		}
	});
	
	//show/hide left menu to switch into full/icon only menu
	jQuery('#togglemenuleft a').click(function(){
		if(jQuery('.mainwrapper').hasClass('lefticon')) {
			jQuery('.mainwrapper').removeClass('lefticon');
			jQuery(this).removeClass('toggle');
			
			//remove all tooltip element upon switching to full menu view
			jQuery('.leftmenu > ul > li').each(function(){
				jQuery(this).find('.subtitle').remove();
				if(jQuery(this).hasClass('current'))
					jQuery(this).find('ul').show().unwrap();
				else
					jQuery(this).find('ul').hide().unwrap();
			});
			
		} else {
			jQuery('.mainwrapper').addClass('lefticon');
			jQuery(this).addClass('toggle');
			
			showTooltipLeftMenu();
		}
	});
	
	function showTooltipLeftMenu() {
		//create tooltip menu upon switching to icon only menu view
		jQuery('.leftmenu > ul > li > a').each(function(){
			var text = jQuery(this).text();																//get the text
			jQuery(this).removeClass('active');															//reset active in switching icon view
			jQuery(this).parent().attr('style','');														//clear style attribute to this menu
			jQuery(this).parent().find('ul').show();													//hide sub menu when there are showed sub menu
			jQuery(this).after('<div class="menutip"><span class="subtitle">'+text+'</span></div>');	//append menu tooltip
			jQuery(this).parent()
				.find('.menutip')
				.append(jQuery(this)
				.parent()
				.find('ul'));
		});	
	}
	
	
	/** FLOAT LEFT SIDEBAR **/
	jQuery(document).scroll(function(){
		var pos = jQuery(document).scrollTop();
		if(pos > 50) {
			jQuery('.floatleft').css({position: 'fixed', top: '10px', right: '10px'});
		} else {
			jQuery('.floatleft').css({position: 'absolute', top: 0, right: 0});
		}
	});
	
	/** FLOAT RIGHT SIDEBAR **/
	jQuery(document).scroll(function(){
		if(jQuery(this).width() > 580) {
			var pos = jQuery(document).scrollTop();
			if(pos > 50) {
				jQuery('.floatright').css({position: 'fixed', top: '10px', right: '10px'});
			} else {
				jQuery('.floatright').css({position: 'absolute', top: 0, right: 0});
			}
		}
	});
	
	
	//NOTIFICATION CLOSE BUTTON
	jQuery('.notification .close').click(function(){
		jQuery(this).parent().fadeOut();
	});
	
	
	//button hover
	jQuery('.btn').hover(function(){
		jQuery(this).stop().animate({backgroundColor: '#eee'});
	},function(){
		jQuery(this).stop().animate({backgroundColor: '#f7f7f7'});
	});
	
	//standard button hover
	jQuery('.stdbtn').hover(function(){
		jQuery(this).stop().animate({opacity: 0.75});
	},function(){
		jQuery(this).stop().animate({opacity: 1});
	});
	
	//buttons in error page
	jQuery('.errorWrapper a').hover(function(){
		jQuery(this).switchClass('default','hover');
	},function(){
		jQuery(this).switchClass('hover', 'default');
	});
	
	
	//screen resize
	var TO = false;
	jQuery(window).resize(function(){
		if(jQuery(this).width() < 1024) {
			jQuery('.mainwrapper').addClass('lefticon');
			jQuery('#togglemenuleft').hide();
			jQuery('.mainright').insertBefore('.footer');
			
			if(jQuery('.menutip').length == 0)
				showTooltipLeftMenu();
			
			if(jQuery(this).width() <= 580) {
				jQuery('.stdtable').wrap('<div class="tablewrapper"></div>');
				
				if(jQuery('.headerinner2').length == 0)
					insertHeaderInner2();
			} else {
				removeHeaderInner2();
			}
			
		} else {
			toggleLeftMenu();
			removeHeaderInner2();
		}
		
	});	
		
	if(jQuery(window).width() < 1024) {
		jQuery('.mainwrapper').addClass('lefticon');
		jQuery('#togglemenuleft').hide();
		jQuery('.mainright').insertBefore('.footer');
		
		if(jQuery('.menutip').length == 0)
			showTooltipLeftMenu();
		
		if(jQuery(window).width() <= 580) {
			jQuery('table').wrap('<div class="tablewrapper"></div>');
			insertHeaderInner2();
		}
			
	} else {
		toggleLeftMenu();
	}
	
	function toggleLeftMenu() {
		if(!jQuery('.mainwrapper').hasClass('lefticon')) {
			jQuery('.mainwrapper').removeClass('lefticon');
			jQuery('#togglemenuleft').show();
		} else {
			jQuery('#togglemenuleft').show();
			jQuery('#togglemenuleft a').addClass('toggle');
		}	
	}
	
	function insertHeaderInner2() {
		jQuery('.headerinner').after('<div class="headerinner2"></div>');
		jQuery('#searchPanel').appendTo('.headerinner2');
		jQuery('#userPanel').appendTo('.headerinner2');
		jQuery('#userPanel').addClass('userinfomenu');
	}
	
	function removeHeaderInner2() {
		jQuery('#searchPanel').insertBefore('#notiPanel');
		jQuery('#userPanel').insertAfter('#notiPanel');
		jQuery('#userPanel').removeClass('userinfomenu');
		jQuery('.headerinner2').remove();
	}
	
	//autocomplete
	var availableTags = [
			"ActionScript",
			"AppleScript",
			"Asp",
			"BASIC",
			"C",
			"C++",
			"Clojure",
			"COBOL",
			"ColdFusion",
			"Erlang",
			"Fortran",
			"Groovy",
			"Haskell",
			"Java",
			"JavaScript",
			"Lisp",
			"Perl",
			"PHP",
			"Python",
			"Ruby",
			"Scala",
			"Scheme"
		];
	jQuery( "#keyword" ).autocomplete({
		source: availableTags
	});
		
	
});