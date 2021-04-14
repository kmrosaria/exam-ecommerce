@extends('admin.layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col"> 
        <div class="cart-container">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Product</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $product)
                <tr>
                  <td>{{ __($product->name)}}</td>
                  <td>{{ __($product->description)}}</td>
                  <td>{{ number_format($product->price, 2, '.', ',')}}</td>
                  <td>{{ __($product->quantity)}}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">No Products</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
@endsection