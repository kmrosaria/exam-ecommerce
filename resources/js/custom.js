
$( document ).ready(function() {
	let url = '/api/cart/1';
	fetch(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest"
            },
        method: 'get',
        credentials: "same-origin"
    })
	.then(response => response.json())
	.then(data => {
		// get total cart quantity
		let summary = orderSummary(data.cart.items);
		$('#cart-btn .cart-qty').text(summary.qty);
		$('.order-summary-container .total-qty').text(summary.qty);
		$('.order-summary-container .sub-total').text(summary.subTotal);
		$('.order-summary-container .shipping-fee').text(summary.shippingFee);
		$('.order-summary-container .total-price').text(summary.total);

		if (parseInt(summary.qty) == 0) {
			$('#checkout-btn').attr('disabled', 'disabled');
		}
	})
    .catch(function(error) {
        console.log(error);
    });
});

$( ".add-to-cart" ).click(function() {
	let url = '/api/cart/add';
	let uuid = $(this).data('product-uuid');
	let field = $(this).data('field');
	let redirect = $(this).data('redirect');
	let itemQty = $("input[name='"+field+"']").val();

	fetch(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest"
            },
        method: 'post',
        credentials: "same-origin",
        body: JSON.stringify({
            uuid: uuid,
            userid: '1',
            quantity: itemQty
        })
    })
	.then(response => response.json())
	.then(data => {

		let alertMsg = $('div.alert.response-msg');
		alertMsg.text(data.message).removeClass('d-none');

		if (data.status === 'success') {
			// get total cart quantity
			let summary = orderSummary(JSON.parse(data.data.items));
			$('#cart-btn .cart-qty').text(summary.qty);

			alertMsg.addClass('alert-success').removeClass('alert-danger');
		} else {
			alertMsg.addClass('alert-danger').removeClass('alert-success');
		}


		if (typeof redirect !== 'undefined') {
			window.location.href = "/cart/1";
		}
	})
    .catch(function(error) {
        console.log(error);
    });
});


$( ".remove-to-cart" ).click(function() {
	let url = '/api/cart/remove';
	let uuid = $(this).data('product-uuid');
	let userId = $(this).data('user-id');

	fetch(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest"
            },
        method: 'post',
        credentials: "same-origin",
        body: JSON.stringify({
            uuid: uuid,
            userid: userId,
        })
    })
	.then(response => response.json())
	.then(data => {
		window.reload();
	})
    .catch(function(error) {
        console.log(error);
    });
});

$( ".item-quantity" ).change(function() {
    uuid = $(this).attr('data-uuid');
    var currentVal = parseInt($(this).val());
	let url = '/api/cart/update';

	fetch(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest"
            },
        method: 'post',
        credentials: "same-origin",
        body: JSON.stringify({
            uuid: uuid,
            userid: '1',
            quantity: currentVal
        })
    })
	.then(response => response.json())
	.then(data => {
		let summary = orderSummary(JSON.parse(data.data.items));
		$('#cart-btn .cart-qty').text(summary.qty);
		$('.order-summary-container .total-qty').text(summary.qty);
		$('.order-summary-container .sub-total').text(summary.subTotal);
		$('.order-summary-container .shipping-fee').text(summary.shippingFee);
		$('.order-summary-container .total-price').text(summary.total);
	})
    .catch(function(error) {
        console.log(error);
    });
});


function orderSummary(items) {
	var cartQty = 0;
	var subTotal = 0;
	var shippingFee = 0;

	Object.entries(items).forEach(([key, value]) => {
		cartQty += parseInt(value.quantity);
		subTotal += (parseInt(value.price) * parseInt(value.quantity));
	});

	shippingFee = 50;

	return {
		qty: cartQty,
		subTotal: ' ₱' + subTotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
		shippingFee: ' ₱' + shippingFee.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'),
		total: ' ₱' + (subTotal + shippingFee).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
	}
}

$('#checkout-btn').click(function() {
	window.location.href = $(this).data('url');
});

$('#checkoutForm').submit(function(e) {
    e.preventDefault();

    var data = $(this).serializeArray().reduce(function(obj, item) {
	    obj[item.name] = item.value;
	    return obj;
	}, {});

	data.userId = $(this).attr('data-user-id');

	let url = '/api/process-checkout';

	fetch(url, {
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json, text-plain, */*",
            "X-Requested-With": "XMLHttpRequest"
            },
        method: 'post',
        credentials: "same-origin",
        body: JSON.stringify(data)
    })
	.then(response => response.json())
	.then(data => {
		let alertMsg = $('div.alert.response-msg');
		alertMsg.text(data.message).removeClass('d-none');
		if (data.status === 'success') {
			alertMsg.addClass('alert-success').removeClass('alert-danger');

			setTimeout(function() {
				window.location.href = "/";
			}, 2000);
		} else {
			alertMsg.addClass('alert-danger').removeClass('alert-success');
		}
	})
    .catch(function(error) {
        console.log(error);
    });
})


$('.btn-number').click(function(e){
    e.preventDefault();
    
    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
            
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $("button[name='"+fieldName+"']").attr('disabled', false);
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $("button[name='"+fieldName+"']").attr('disabled', false);
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
   $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        $(this).val(maxValue);
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        $(this).val(maxValue);
    }
});
    
    
