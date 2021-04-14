@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-7"> 
        <div class="checkout-form-container p-2">
            <div class="alert alert-danger d-none response-msg" role="alert"></div>
        	<form name="checkoutForm" id="checkoutForm" data-user-id="{{ __($user->id)}}">
			  <div class="form-group">
			    <label for="name">Full Name</label>
			    <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter full name" value="{{ __($user->name)}}" required>
			  </div>
			  <div class="form-group">
			    <label for="address">Address</label>
			    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Complete Address" required>
			  </div>
			  <div class="form-group">
			    <label for="address">Contact Number</label>
			    <input type="number" class="form-control" id="number" name="number" placeholder="Enter Contact Number" required>
			  </div>
			  <div class="form-group">
			    <label for="address">Payment Method</label>
				<select class="form-control" id="method" name="method" >
				  <option value="cod">COD</option>
				  <option value="creditcard">Credit Card</option>
				  <option value="gcash">Gcash</option>
				</select>
			  </div>
			  <button type="submit" class="btn btn-primary btn-block">Place orders</button>
			</form>
        </div>

      </div>
      <div class="col-5"> 
        <div class="px-4 py-5 order-summary-container">
            <div class="d-flex justify-content-between"> <span class="font-weight-bold">Subtotal(Qty:<span class="total-qty">0</span>)</span> <span class="text-muted sub-total">0.00</span> </div>
            <div class="d-flex justify-content-between"> <small>Shipping</small> <small class="shipping-fee"> 50.00</small> </div>
            <div class="d-flex justify-content-between mt-3"> <span class="font-weight-bold">Total</span> <span class="font-weight-bold total-price">0.00</span> </div>
        </div>
      </div>
    </div>
</div>
@endsection