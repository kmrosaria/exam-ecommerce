@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col-7 "> 
        <div class="cart-container">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Cart</th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr>
                  <td>
                    <img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20200%20200%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_178cfee9a39%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A10pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_178cfee9a39%22%3E%3Crect%20width%3D%22200%22%20height%3D%22200%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2274.4296875%22%20y%3D%22104.5%22%3E200x200%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true" style="width: 200px;">
                  </td>
                  <td>
                    <h3>{{ __($item->name) }}</h3>
                    <p>₱{{ __($item->price) }}</p>
                  </td>
                  <td>
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <button type="button" name="quant[{{ __($item->uuid) }}]" class="btn btn-outline-secondary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{ __($item->uuid) }}]">
                                -
                            </button>
                        </span>
                        <input type="text" name="quant[{{ __($item->uuid) }}]" class="form-control input-number item-quantity" value="{{ __($cart->items[$item->uuid]['quantity']) }}" min="1" max="{{ __($item->quantity) }}" data-uuid="{{ __($item->uuid) }}">
                        <span class="input-group-append">
                            <button type="button" name="quant[{{ __($item->uuid) }}]" class="btn btn-outline-secondary btn-number" data-type="plus" data-field="quant[{{ __($item->uuid) }}]">
                                +
                            </button>
                        </span>
                    </div>
                  </td>
                  <td>
                    <button type="button" class="btn btn-danger remove-to-cart" data-product-uuid="{{ __($item->uuid)}}" data-user-id="{{ __($user->id)}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                      </svg>
                    </button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">No Items</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
      <div class="col-5"> 
        <div class="px-4 py-5 order-summary-container">
            <h5 class="text-uppercase">{{ __($user->name)}}</h5>
            <div class="mb-3">
                <hr class="new1">
            </div>
            <div class="d-flex justify-content-between"> <span class="font-weight-bold">Subtotal(Qty:<span class="total-qty">0</span>)</span> <span class="text-muted sub-total">0.00</span> </div>
            <div class="d-flex justify-content-between"> <small>Shipping</small> <small class="shipping-fee"> 50.00</small> </div>
            <div class="d-flex justify-content-between mt-3"> <span class="font-weight-bold">Total</span> <span class="font-weight-bold total-price">0.00</span> </div>
            <div class="text-center mt-5"> <button class="btn btn-primary" id="checkout-btn" data-url="{{ url('/checkout/' . $user->id) }}">Checkout</button> </div>
        </div>
      </div>
    </div>
</div>
@endsection