<meta charset="utf-8">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<meta name="keywords" content="<?php print(config_metakey); ?>">
<meta name="description" content="<?php print(config_metades); ?>">
<title><?php print(config_sitetitle); ?></title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/slick-theme.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.css">
<link rel="stylesheet" type="text/css" href="css/jquery.simpleGallery.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link href="css/responsive.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="./backend/assets/style/jquery-ui-1.10.3.custom.min.css" />
<script src="js/jquery-2.2.0.min.js"></script>
<script src="./backend/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.simpleGallery.js"></script>
<script type="text/javascript" src="js/jquery.simpleLens.js"></script>
<style>
    .ui-widget.ui-widget-content{
        z-index: 9999999;
        padding: 20px 10px;
        font-size: 18px;
    }
</style>
<script>
    $(document).ready(function(){
        $('#demo-1 .simpleLens-thumbnails-container img').simpleGallery({
            loading_image: 'demo/images/loading.gif'
        });

        $('#demo-1 .simpleLens-big-image').simpleLens({
            loading_image: 'demo/images/loading.gif'
        });
    });
</script>
<script>
    function blinker() {
        $('.nav_sale').fadeOut(500);
        $('.nav_sale').fadeIn(500);
    }
    setInterval(blinker, 1000);
</script>
<script>
    $(() => {

        //On Scroll Functionality
        $(window).scroll(() => {
            var windowTop = $(window).scrollTop();
            windowTop > 10 ? $('.header_sticky').addClass('headersticky') : $('.header_sticky').removeClass('headersticky');
        });

        //Click Logo To Scroll To Top
        $('#scroll_top').on('click', () => {
            $('html,body').animate({
                scrollTop: 0
            }, 500);
        });

        //Smooth Scrolling Using Navigation Menu

        $('a[href*="#"]').on('click', function(e) {
            $('html,body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 15 /*Fixed header k liye is ko uncomment krna hai*/
            }, 500);
            e.preventDefault();
        });

        //Toggle Menu
        $('.all_menu').on('click', () => {
            $('.all_menu').toggleClass('closeMenu');
            $('.nav_submenu').toggleClass('showMenu');

            $('.nav_overlay, .submenu_close').on('click', () => {
                $('.nav_submenu').removeClass('showMenu');
                $('.nav_submenu').removeClass('closeMenu');
            });
        });
    });
</script>

<script>
	$(window).load(function () {
		/*2 popup 1 hide 1 show*/
		$(".location_trigger").click(function () { $('.location_popup').show(); $('.location_popup').resize(); $('body').css({ 'overflow': 'hidden' }); });
		$('.location_close').click(function () { $('.location_popup').hide(); $('body').css({ 'overflow': 'inherit' }); });

	});

</script>
<script>
    $(document).ready(function() {
        $(".side_cart_click").click(function() {
            $(".hdr_side_cart").show();
            $("#container").css('padding-right', '130px');
            $("#logo").css('width', '120px');
            show_side_cart_data();
        });
        $(".hdr_side_cart .side_bar_close").click(function() {
            $(".hdr_side_cart").hide();
            $("#container").css('padding-right', '0px');
            $("#logo").css('width', '150px');
        });

    });
</script>

<script>
	$(document).ready(function(){
	  $(".filter_mobile, .categroy_close_mb").click(function(){
		$(".pd_left").toggle();
	  });
	});
</script>
<script>
	$(function() {
	$( "#slider-range" ).slider({
	  range: true,
	  min: 130,
	  max: 500,
	  values: [ 130, 250 ],
	  slide: function( event, ui ) {
		$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
	  }
	});
	$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
	  " - $" + $( "#slider-range" ).slider( "values", 1 ) );
});
</script>
