<meta charset="utf-8">
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<title>Wacker 24</title>
<link href="css/styles.css" rel="stylesheet" type="text/css" />
<link href="css/slick-theme.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.css">
<link rel="stylesheet" type="text/css" href="css/jquery.simpleGallery.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link href="css/responsive.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-2.2.0.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery.simpleGallery.js"></script>
<script type="text/javascript" src="js/jquery.simpleLens.js"></script>

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
    $(function() {
        //console.log("switch_click");
        $(".switch_click").click(function() {
            //console.log("class switch_click");
            if ($(this).is(":checked")) {
                //console.log("if switch_click");
                $("#header_section .header_top").css('background-color', '#4884fc');
                $("#header_section .header_bottom").css('background-color', '#011f43');
                $("#navigation_section").css('background-color', '#4884fc');
                $("#footer_section").css('background-color', '#4884fc');
                $(".pbp_price_with_tex").hide();
                $(".price_without_tex").show();
            } else {
                //console.log("else switch_click");
                $("#header_section .header_top").css('background-color', '#323234');
                $("#header_section .header_bottom").css('background-color', '#cc0f19');
                $("#navigation_section").css('background-color', '#323234');
                $("#footer_section").css('background-color', '#323234');
                $(".pbp_price_with_tex").show();
                $(".price_without_tex").hide();
            }
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
