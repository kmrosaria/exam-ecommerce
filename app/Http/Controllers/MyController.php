<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Carts;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;

class MyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllProducts(Request $request)
    {
        $result = Products::all();

        return $this->isApi($request->is('api/*'), $result, 'products', 'products');
    }

    /**
     * Get product details.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductById(Request $request, $id)
    {
        $result = Products::where([
            ['uuid', "$id"],
            ['enable', true],
        ])->firstOrFail();



        return $this->isApi($request->is('api/*'), $result, 'product', 'product');
    }


    /**
     * get current cart by user api.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartByUser(Request $request, $userId)
    {
        $user = User::find($userId);
        $result = $user->cart
                ->where('active', true)
                ->first();

        return $this->isApi($request->is('api/*'), $result);
    }


    /**
     * Add to cart api.
     *
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request)
    {
        $userId = $request->userid;
        $productUuId = $request->uuid;
        $quantity = $request->quantity;

        // get product
        $product = Products::where([
            ['uuid', "$productUuId"],
            ['enable', true],
        ])->firstOrFail();

        //check if existing 
        if (!$product) {
            return;
        }

        $user = User::find($userId);

        //check if existing 
        if (!$user) {
            return;
        }

        $cart = null;
        $cartQuery = Carts::where('active', 1)
          ->where('customer_id', "$userId");

        $cart = $cartQuery->first();

        $result = 0;
        if (!$cart) {
            // create a cart
            $items = [$product->uuid => [
                'name' => $product->name,
                'description' => $product->description,
                'image' => $product->image,
                'price' => $product->price,
                'price' => $product->price,
                'quantity' => $quantity
            ]];
            $items = json_encode($items);

            $result = $user->cart()->create(
                [
                    'total_price' => $product->price,
                    'items' => $items,
                    'active' => true,
                    'customer_id' => "$userId",
                ]
            );

        } else {
            //update current cart
            $item = [
                'name' => $product->name,
                'description' => $product->description,
                'image' => $product->image,
                'price' => $product->price,
                'price' => $product->price,
                'quantity' => $quantity
            ];


            $items = json_decode($cart->items, true);
            if (isset($items[$product->uuid])) {
                $items[$product->uuid]['quantity'] += $quantity;
            } else {
                $items[$product->uuid] = $item;
            }

            $newTotalPrice = $cart->total_price + ($product->price * $quantity);

            $cartQuery = Carts::where('active', 1)
              ->where('customer_id', "$userId");

            $result = $cartQuery->update(['total_price' => $newTotalPrice, 'items' => $items]);
        }

        if($result){
            return response()->json([
                'status' => 'success',
                'data' => $cartQuery->first(),
                'message' => 'Cart has been updated'
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'data' => $cartQuery->first(),
                'message' => 'Encountered an error. Please try again'
            ], 200);
        }

    }


    /**
     * Adjust product quantity api.
     *
     * @return \Illuminate\Http\Response
     */
    // public function Adjus quantity(Request $request, $id)
    // {
    // }

    /**
     * Check if request is from API or Web
     *
     */
    private function isApi ($isApi, $data, $template = null, $varName = null)
    {
        if(!$isApi && ($template && $varName)) {
            return response()->view($template, [$varName => $data]);
        }

        return response()->json($data);

    }
}
