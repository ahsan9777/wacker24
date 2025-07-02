
<link href="css/slick-theme.min.css" rel="stylesheet" type="text/css" />
<!-- <link rel="stylesheet" type="text/css" href="css/jquery.simpleLens.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery.simpleGallery.min.css">-->
<link rel="stylesheet" href="css/jquery-ui.min.css">
<link href="css/responsive.min.css" rel="stylesheet" type="text/css" />
<link href="css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="./backend/assets/style/jquery-ui-1.10.3.custom.min.css" />
<!--<script defer src="js/jquery-ui.min.js"></script>
<script defer type="text/javascript" src="js/jquery.simpleGallery.min.js"></script>
<script defer type="text/javascript" src="js/jquery.simpleLens.min.js"></script>-->
<script>
    setTimeout(function() {
        const scripts = [
            "js/jquery-ui.min.js"
        ];

        scripts.forEach(function(src) {
            const script = document.createElement("script");
            script.src = src;
            script.defer = true;
            document.body.appendChild(script);
            //console.log(src + " loaded after 5 seconds");
        });
    }, 10000);
</script>


<script>
    $(function() {
        //console.log("switch_click");
        //$(".switch_click").trigger("click");
        $(".switch_click").click(function() {
            //console.log("class switch_click");
            let utype_id = 3;
            let ci_total = 0;
            if ($(this).is(":checked")) {
                //console.log("if switch_click");
                utype_id = 4;
                $("#header_section .header_top").css('background-color', '<?php print(config_company_color_a); ?>');
                $("#header_section .header_bottom").css('background-color', '<?php print(config_company_color_b); ?>');
                $("#navigation_section").css('background-color', '<?php print(config_company_color_a); ?>');
                $("#footer_section").css('background-color', '<?php print(config_company_color_a); ?>');
                $("#switch_click_text").text("Angebote gelten nur für Industrie, Handel, Handwerk und Gewerbe. Preise zzgl. gesetzl. MwSt.");
                $(".pbp_price_with_tex").hide();
                $(".price_without_tex").show();
                ci_total = $("#ci_total").val();
            } else {
                //console.log("else switch_click");
                $("#header_section .header_top").css('background-color', '<?php print(config_private_color_a); ?>');
                $("#header_section .header_bottom").css('background-color', '<?php print(config_private_color_b); ?>');
                $("#navigation_section").css('background-color', '<?php print(config_private_color_a); ?>');
                $("#footer_section").css('background-color', '<?php print(config_private_color_a); ?>');
                $("#switch_click_text").text("Angebote gelten für Privatkunden. Preise inkl. gesetzl. MwSt.");
                $(".pbp_price_with_tex").show();
                $(".price_without_tex").hide();
                ci_total = $("#ci_total").val();
            }

            $.ajax({
                url: 'ajax_calls.php?action=switch_click',
                method: 'POST',
                data: {
                    utype_id: utype_id,
                    ci_total: ci_total
                },
                success: function(response) {
                    //console.log("response = "+response);
                    const obj = JSON.parse(response);
                    //console.log(obj.delivery_charges.total);
                    if (obj.status == 1) {
                        if (obj.delivery_charges.tex > 0) {
                            $("#cart_subtotal").show();
                            $("#cart_vat").show();
                        } else {
                            if (obj.delivery_charges.total == 0 && obj.utype_id == 4) {
                                $("#cart_subtotal").show();
                                $("#cart_vat").show();
                            } else {
                                $("#cart_subtotal").hide();
                                $("#cart_vat").hide();
                            }
                        }
                        let packing = (obj.delivery_charges.packing).toFixed(2)
                        let shipping = (obj.delivery_charges.shipping).toFixed(2)
                        let total = (obj.delivery_charges.total).toFixed(2)
                        $("#packing").text("Verpackungspauschale  (" + packing.replace(".", ",") + " €)");
                        $("#shipping").text("Versandkosten (" + shipping.replace(".", ",") + " €)");
                        $("#total").text(total.replace(".", ",") + " €");
                    } else {
                        if (obj.utype_id == 4) {
                            $("#cart_subtotal").show();
                            $("#cart_vat").show();
                        } else {
                            $("#cart_subtotal").hide();
                            $("#cart_vat").hide();
                        }
                    }

                }
            });
        });
    });
</script>

<style>
    .ui-widget.ui-widget-content{
        z-index: 9999999;
        padding: 20px 10px;
        font-size: 18px;
    }
</style>
<script>
    function blinker() {
        $('.nav_sale').fadeOut(500);
        $('.nav_sale').fadeIn(500);
    }
    setInterval(blinker, 2000);
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

        /*$('a[href*="#"]').on('click', function(e) {
            $('html,body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 15 Fixed header k liye is ko uncomment krna hai
            }, 500);
            e.preventDefault();
        });*/

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
        function applyPadding() {
            if (window.matchMedia("(max-width: 240px)").matches || window.matchMedia("(max-width: 1024px)").matches) {
                $("#container").css('padding-right', '0px');
            } else {
                $("#container").css('padding-right', '130px');
            }
        }

        $(".side_cart_click").click(function() {
            $(".hdr_side_cart").show();
            applyPadding();
            $("#logo").css('width', '120px');
            show_side_cart_data();
        });
        $(".hdr_side_cart .side_bar_close").click(function() {
            $(".hdr_side_cart").hide();
            $("#container").css('padding-right', '0px');
            $("#logo").css('width', '150px');
        });
        // Apply padding on window resize
        $(window).resize(function() {
            if ($(".hdr_side_cart").is(":visible")) {
                applyPadding();
            }
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

<script>
	$(document).ready(function(){
	  $("#header_section .header_bottom .header_search .search_input").click(function(){
		$(".header_overlay").show();
		$("#header_section .header_bottom .header_search").css("border", "2px solid yellow");
		$("#header_section .header_bottom .header_search .header_select_slt").css("border", "0px solid yellow");
	  });
	  $(".header_overlay").click(function(){
		$(".header_overlay").hide();
		$("#header_section .header_bottom .header_search").css("border", "0px solid yellow");
		$("#header_section .header_bottom .header_search .header_select_slt").css("border", "0px solid yellow");
	  });
	  $("#header_section .header_bottom .header_search .header_select_slt").click(function(){
		$(".header_overlay").show();
		$("#header_section .header_bottom .header_search .header_select_slt").css("border", "2px solid yellow");
		$("#header_section .header_bottom .header_search").css("border", "0px solid yellow");
		});
		
	});
</script>
<script>
	$('#header_section .header_bottom .header_search .header_select_slt').change(function(){
	var text = $(this).find('option:selected').text()
	var $aux = $('<select/>').append($('<option/>').text(text))
	$(this).after($aux)
	$(this).width($aux.width())
	$aux.remove()
	}).change()
</script>
<script src="./backend/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" defer></script> -->
<script>
	$('.close').on('click', function(){
        $('.alert').hide();
    });
	$("#level_one").on("change", function(){
		$("#pro_id").val(0);
        $("#search_keyword").val("");
	});
	$('input.search_keyword').autocomplete({
            source: function(request, response) {
				let level_one = $("#level_one").val();
                $.ajax({
                    url: 'ajax_calls.php?action=search_keyword&level_one='+level_one+'',
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response(data);

                    }
                });
            },
            minLength: 1,
            select: function(event, ui) {
                var supplier_id = $("#supplier_id");
                var search_keyword = $("#search_keyword");
                $(supplier_id).val(ui.item.supplier_id);
                $(search_keyword).val(ui.item.value);
                frm_search.submit();
                //return false;
            }
        });
// Also update the code in product.php file
	$(".add_to_card").on("click", function(){
		//console.log("add_to_card");
		let pro_id = $("#pro_id_"+$(this).attr("data-id")).val();
		let pro_type = $("#pro_type_"+$(this).attr("data-id")).val();
		let supplier_id = $("#supplier_id_"+$(this).attr("data-id")).val();
		let ci_type = $("#ci_type_"+$(this).attr("data-id")).val();
		let ci_discount_type = $("#ci_discount_type_"+$(this).attr("data-id")).val();
		let ci_discount_value = $("#ci_discount_value_"+$(this).attr("data-id")).val();
		let ci_qty = $("#ci_qty_"+$(this).attr("data-id")).val();
		let ci_qty_type = $("#ci_qty_type_"+$(this).attr("data-id")).val();

		/*console.log("pro_type: "+pro_type);
		console.log("supplier_id: "+supplier_id);
		console.log("ci_qty: "+ci_qty);*/

		$.ajax({
			url: 'ajax_calls.php?action=add_to_card',
			method: 'POST',
			data: {
				pro_id: pro_id,
				pro_type: pro_type,
				supplier_id: supplier_id,
				ci_type: ci_type,
				ci_discount_type: ci_discount_type,
				ci_discount_value: ci_discount_value,
				ci_qty: ci_qty,
				ci_qty_type: ci_qty_type
			},
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if(obj.status == 1){
					$("#header_quantity").text(obj.count+" items");
					$(".side_cart_click").trigger("click");
				}
			}
		});
	});

    function show_side_cart_data(){
        //console.log("show_side_cart_data");
        $.ajax({
			url: 'ajax_calls.php?action=show_side_cart_data',
			method: 'POST',
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				//console.log(obj);
				if(obj.status == 1){
					$("#header_quantity").text(obj.count+" Artikel");
					$("#show_card_body").html(obj.show_card_body);
					$("#cart_amount").text(obj.cart_amount+" €");
					$("#cart_click_href").attr("href", "einkaufswagen");
					$("#cart_href").attr("href", "einkaufswagen");
				} else{
					$("#header_quantity").text(obj.count+" Artikel");
                    $("#show_card_body").html(obj.show_card_body);
					$("#cart_amount").text(obj.cart_amount+" €");
					$("#cart_click_href").attr("href", "javascript:void(0)");
					$("#cart_href").attr("href", "javascript:void(0)");
                }
			}
		});
    }

    
</script>