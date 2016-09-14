/* Toggle Main Navigation and Search */
jQuery(document).ready(function(){

	jQuery(".menu-btn").click(function(){
		jQuery("#fly-out-nav").slideToggle('400');
		jQuery("#fly-out-search").hide();
	});

	jQuery(".search-btn").click(function(){
		jQuery("#fly-out-search").slideToggle('400');
		jQuery("#fly-out-nav").hide();
	});

	jQuery(".menu-close-btn").click(function(){
		jQuery("#fly-out-nav").slideUp('400');
	});
});


/* LINK & BUTTON COLORS */
/* To prevent sticky css-hover-effects */
jQuery(document).ready(function(){

	jQuery("a").mouseenter(function(){
		jQuery(this).css("color", "rgb(0,160,230)");
	});
	jQuery("#site-branding a, a.read-more, .single .entry-content a, .page .entry-content a, #sidebar .textwidget a, #site-footer .textwidget a, .commentlist a, .comment-reply-title a").mouseenter(function(){
		jQuery(this).css("color", "rgb(0,125,179)");
	});
	jQuery(".format-link .entry-content a").mouseenter(function(){
		jQuery(this).css("color", "rgb(0,160,230)");
	});
	jQuery(".meta-tags a, a.comment-reply-link").mouseenter(function(){
		jQuery(this).css("color", "#404040");
	});

	jQuery("a").on("mouseleave click", function(){
		jQuery(this).css("color", "");
	});

	jQuery("#sociallinks-menu a").mouseenter(function(){
		jQuery(this).css("background-color", "rgb(0,160,230)");
	});
	jQuery(".meta-tags a, a.comment-reply-link, .nav-btn a, button, input[type=button], input[type=reset], input[type=submit]").mouseenter(function(){
		jQuery(this).css("background-color", "rgb(217,215,210)");
	});
	jQuery("input[type=submit].search-submit").mouseenter(function(){
		jQuery(this).css("background-color", "");
	});

	jQuery("#sociallinks-menu a, .meta-tags a, a.comment-reply-link, .nav-btn a, button, input[type=button], input[type=reset], input[type=submit]").on("mouseleave click", function(){
		jQuery(this).css("background-color", "");
	});


	jQuery(".hentry a img").mouseenter(function(){
		jQuery(this).css("opacity", "0.8");
	});
	jQuery(".hentry a img").on("mouseleave click", function(){
		jQuery(this).css("opacity", "");
	});
});


/* MASONRY LAYOUT */
jQuery(document).ready(function(){
		jQuery('#main').masonry({
			columnWidth: '.grid-sizer',
			itemSelector: '.box'
	});
	
	var jQuerycontainer = jQuery('#main').masonry();
	// layout Masonry again after all images have loaded
		jQuerycontainer.imagesLoaded( function() {
			jQuerycontainer.masonry();
	});
});