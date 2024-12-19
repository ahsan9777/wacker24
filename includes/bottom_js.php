<script>
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
					$("#cart_href").attr("href", "cart.php");
                }
			}
		});
    }

    $("#item_deleted").on("click", function(){
        //console.log("item_deleted");
        let ci_id = $(this).attr("data-id").val();
        //console.log("ci_id:"+ci_id);
        $.ajax({
			url: 'ajax_calls.php?action=item_deleted',
			method: 'POST',
            data:{
                ci_id: ci_id
            },
			success: function(response) {
				//console.log("response = "+response);
				const obj = JSON.parse(response);
				console.log(obj);
				if(obj.status == 1){
					$("#header_quantity").text(obj.count+" items");
					$(".side_cart_click").trigger("click");
				}
			}
		});
    });
</script>