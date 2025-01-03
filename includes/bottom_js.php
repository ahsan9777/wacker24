<script>
	$('.close').on('click', function(){
        $('.alert').hide();
    });

	$(".add_to_card").on("click", function(){
		//console.log("add_to_card");
		let pro_id = $("#pro_id_"+$(this).attr("data-id")).val();
		let supplier_id = $("#supplier_id_"+$(this).attr("data-id")).val();
		let ci_qty = $("#ci_qty_"+$(this).attr("data-id")).val();

		/*console.log("pro_id: "+pro_id);
		console.log("supplier_id: "+supplier_id);
		console.log("ci_qty: "+ci_qty);*/

		$.ajax({
			url: 'ajax_calls.php?action=add_to_card',
			method: 'POST',
			data: {
				pro_id: pro_id,
				supplier_id: supplier_id,
				ci_qty: ci_qty
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
					$("#show_card_body").html(obj.show_card_body);
					$("#cart_amount").text(obj.cart_amount+" €");
					$("#cart_href").attr("href", "cart.php");
				} else{
                    $("#show_card_body").html(obj.show_card_body);
					$("#cart_amount").text(obj.cart_amount+" €");
					$("#cart_href").attr("href", "javascript:void(0)");
                }
			}
		});
    }

    
</script>