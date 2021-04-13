
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
		cart = data;
		computeCartQty(cart.items);
	})
    .catch(function(error) {
        console.log(error);
    });
});

$( "#add-to-cart-btn" ).click(function() {
	let url = '/api/cart/add';
	let uuid = $(this).data('product-uuid');

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
            quantity: '1'
        })
    })
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// get total cart quantity
			cart = data.data;
			computeCartQty(cart.items);
		}
	})
    .catch(function(error) {
        console.log(error);
    });
});


function computeCartQty(items) {
	var items = JSON.parse(items);
	var cartQty = 0;

	Object.entries(items).forEach(([key, value]) => {
		cartQty += value.quantity;
	});

	$('#cart-btn .cart-qty').text(cartQty);
}