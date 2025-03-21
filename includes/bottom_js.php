
<script>
	$('#header_section .header_bottom .header_search .header_select_slt').change(function(){
	var text = $(this).find('option:selected').text()
	var $aux = $('<select/>').append($('<option/>').text(text))
	$(this).after($aux)
	$(this).width($aux.width())
	$aux.remove()
	}).change()
</script>
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

	$(".add_to_card").on("click", function(){
		//console.log("add_to_card");
		let pro_id = $("#pro_id_"+$(this).attr("data-id")).val();
		let pro_type = $("#pro_type_"+$(this).attr("data-id")).val();
		let supplier_id = $("#supplier_id_"+$(this).attr("data-id")).val();
		let ci_discount_type = $("#ci_discount_type_"+$(this).attr("data-id")).val();
		let ci_discount_value = $("#ci_discount_value_"+$(this).attr("data-id")).val();
		let ci_qty = $("#ci_qty_"+$(this).attr("data-id")).val();

		/*console.log("pro_type: "+pro_type);
		console.log("supplier_id: "+supplier_id);
		console.log("ci_qty: "+ci_qty);*/

		$.ajax({
			url: 'ajax_calls.php?action=add_to_card',
			method: 'GET',
			data: {
				pro_id: pro_id,
				pro_type: pro_type,
				supplier_id: supplier_id,
				ci_discount_type: ci_discount_type,
				ci_discount_value: ci_discount_value,
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