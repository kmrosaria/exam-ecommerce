@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-8 product-container"> 
        <div class="row card">
          <img class="card-img-top" data-src="holder.js/100px180/" alt="100%x180" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22286%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20286%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_178cbc29bc5%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A14pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_178cbc29bc5%22%3E%3Crect%20width%3D%22286%22%20height%3D%22180%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22107.203125%22%20y%3D%2296.3%22%3E286x180%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
          <div class="card-body">
            <h2 class="card-title">{{ __($product->name) }}</h2>
            <p class="card-text">{{ ucfirst($product->description) }}</p>

            <h3> ₱{{ __($product->price) }}</h3>
            <div class="container py-4">
              <div class="row">
                  <label class="text-center col-sm-3">Quantity</label>
                  <div class="col-sm-4">
                      <div class="input-group">
                          <span class="input-group-prepend">
                              <button type="button" class="btn btn-outline-secondary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{ __($product->uuid) }}]">
                                  -
                              </button>
                          </span>
                          <input type="text" name="quant[{{ __($product->uuid) }}]" class="form-control input-number" value="1" min="1" max="{{ __($product->quantity) }}" id="item-quantity">
                          <span class="input-group-append">
                              <button type="button" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="quant[{{ __($product->uuid) }}]">
                                  +
                              </button>
                          </span>
                      </div>
                  </div>
              </div>
            </div>
            <p></p>

            <div class="alert alert-danger d-none response-msg" role="alert"></div>
            <div class="row">
              <button type="button" class="btn btn-primary col mx-1 add-to-cart" id="buy-now-btn" data-product-uuid="{{ __($product->uuid) }}" data-redirect="{{ url('/cart/1') }}" data-field="quant[{{ __($product->uuid) }}]">Buy Now</button>
              <button type="button" class="btn btn-secondary col mx-1 add-to-cart" id="add-to-cart-btn" data-product-uuid="{{ __($product->uuid) }}" data-field="quant[{{ __($product->uuid) }}]">Add to Cart</button>
            </div>
          </div>
        </div> 
      </div>
    </div>
</div>
@endsection