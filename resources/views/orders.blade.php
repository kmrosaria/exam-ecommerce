@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <div class="col"> 
        <div class="cart-container">
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">Order #</th>
                <th scope="col"></th>
                <th scope="col">Shipping Address</th>
                <th scope="col">Contact Number</th>
                <th scope="col">Payment Method</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
                <tr>
                  <td>{{ __($order->id)}}</td>
                  <td>{{ __($order->name)}}</td>
                  <td>{{ ucwords($order->address)}}</td>
                  <td>{{ __($order->number)}}</td>
                  <td>{{ strtoupper($order->payment_method)}}</td>
                  <td>{{ number_format($order->total_price, 2, '.', ',')}}</td>
                  <td>{{ __($order->total_quantity)}}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">No Orders</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
</div>
@endsection