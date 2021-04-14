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

        return $this->processResponse($request->is('api/*'), ['products' => $result], 'products');
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



        return $this->processResponse($request->is('api/*'), ['product' => $result], 'product');
    }


    /**
     * get current cart by user api.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCartByUser(Request $request, $userId)
    {
        $result = $this->getCart($userId);

        //check if existing 
        if (!$result['user']) {
            return abort(404);
        }

        //check if existing 
        if (!$result['cart']) {
            return abort(404);
        }

        $result['cart']->items = json_decode($result['cart']->items, true);
        $itemIds = array_keys($result['cart']->items);

        $cartProducts = Products::where('enable', 1)
            ->whereIn('uuid', $itemIds)->get();

        $result['items'] = $cartProducts;

        return $this->processResponse($request->is('api/*'), $result, 'cart');
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
        $addQuantity = $request->quantity;
        $cart = null;
        $successMessage = '';

        // get product
        $product = Products::where([
            ['uuid', "$productUuId"],
            ['enable', true],
        ])->firstOrFail();

        //check if existing 
        if (!$product) {
            return $this->emptyResultResponse('product');
        }


        $details = $this->getCart($userId);

        //check if existing 
        if (!$details['user']) {
            return $this->emptyResultResponse('user');
        }   

        $cartQuery = Carts::where('active', 1)
          ->where('customer_id', "$userId");

        if (!$details['cart']) {
            // create a cart
            $items = [$product->uuid => [
                'quantity' => $addQuantity,
                'price' => $product->price
            ]];
            $items = json_encode($items);

            $result = $details['user']->cart()->create(
                [
                    'items' => $items,
                    'active' => true,
                    'customer_id' => "$userId",
                ]
            );
        } else {
            //update current cart
            $item = [
                'quantity' => $addQuantity,
                'price' => $product->price
            ];

            $items = json_decode($details['cart']->items, true);
            if (isset($items[$product->uuid])) {
                $remainingQty = $product->quantity - $items[$product->uuid]['quantity'];

                if ($remainingQty == 0) {
                    return response()->json([
                        'status' => 'failed',
                        'statusCode' => 'ExceedQuantity',
                        'data' => '{}',
                        'message' => 'Exceed quantity'
                    ]);
                } else {
                    if ($addQuantity > $remainingQty) {
                        $addQuantity = $remainingQty;
                    }

                    $currentQty = $items[$product->uuid]['quantity'];
                    $items[$product->uuid]['quantity'] = $currentQty + $addQuantity;
                }
            } else {
                $items[$product->uuid] = $item;
            }

            $result = $cartQuery->update(['items' => $items]);
        }

        if ($result) {
            return response()->json([
                'status' => 'success',
                'statusCode' => 'CartSuccess',
                'data' => $cartQuery->first(),
                'message' => 'Added ' . $addQuantity . ' ' . $product->name . ' to cart.'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'statusCode' => 'EncounteredError',
                'data' => $cartQuery->first(),
                'message' => 'Encounter an error. Please try again'
            ]);
        }

    }

    /**
     * Update to cart api.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCart(Request $request)
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
            return $this->emptyResultResponse('product');
        }

        $details = $this->getCart($userId);

        //check if existing 
        if (!$details['user']) {
            return $this->emptyResultResponse('user');
        }

        //check if existing 
        if (!$details['cart']) {
            return $this->emptyResultResponse('cart');
        }

        $cartQuery = Carts::where('active', 1)
          ->where('customer_id', "$userId");

        $result = 0;

        $items = json_decode($details['cart']->items, true);

        if (isset($items[$product->uuid])) {

            if ($quantity > $product->quantity) {
                $quantity = $product->quantity;
            }

            if ($quantity === $items[$product->uuid]['quantity']) {
                return response()->json([
                    'status' => 'failed',
                    'statusCode' => 'SameQuantity',
                    'message' => 'Item has already had the same quantity'
                ]);
            }

            $items[$product->uuid]['quantity'] = $quantity;
        }   

        $cartQuery = Carts::where('active', 1)
          ->where('customer_id', "$userId");

        $result = $cartQuery->update(['items' => $items]);

        return response()->json([
            'status' => 'success',
            'statusCode' => 'CartSuccess',
            'data' => $cartQuery->first(),
            'message' => 'Update ' . $product->name . ' to ' . $quantity
        ]);
    }



    /**
     * Remove to cart api.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeToCart(Request $request)
    {
        $userId = $request->userid;
        $productUuId = $request->uuid;

        // get product
        $product = Products::where([
            ['uuid', "$productUuId"],
            ['enable', true],
        ])->firstOrFail();


        //check if existing 
        if (!$product) {
            return $this->emptyResultResponse('product');
        }

        $details = $this->getCart($userId);

        //check if existing 
        if (!$details['user']) {
            return $this->emptyResultResponse('user');
        }

        //check if existing 
        if (!$details['cart']) {
            return $this->emptyResultResponse('cart');
        }

        $cartQuery = Carts::where('active', 1)
          ->where('customer_id', "$userId");

        $result = 0;

        $items = json_decode($details['cart']->items, true);

        if (isset($items[$product->uuid])) {
            unset($items[$product->uuid]);
        }   

        $cartQuery = Carts::where('active', 1)
          ->where('customer_id', "$userId");

        $result = $cartQuery->update(['items' => $items]);

        return response()->json([
            'status' => 'success',
            'statusCode' => 'CartSuccess',
            'data' => $cartQuery->first(),
            'message' => 'Removed Product'
        ]);
    }


    /**
     * get current cart by user api.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkoutCart(Request $request, $userId)
    {

        $details = $this->getCart($userId);

        //check if existing 
        if (!$details['user']) {
            return abort(404);
        }

        //check if existing 
        if (!$details['cart']) {
            return abort(404);
        }

        //check if cart has items 
        $items = json_decode($details['cart']->items, true);
        if (empty($items)) {
            return abort(404);
        }

        return $this->processResponse(0, $details, 'checkout');
    }


    
    /**
     * get current cart by user api.
     *
     * @return \Illuminate\Http\Response
     */
    public function processCheckout(Request $request)
    {
        $details = $this->getCart($request->userId);

        //check if existing 
        if (!$details['user']) {
            return $this->emptyResultResponse('user');
        }

        //check if existing 
        if (!$details['cart']) {
            return $this->emptyResultResponse('cart');
        }

        // recompute total price
        $items = json_decode($details['cart']->items, true);
        $sub_total = $total_price = $total_qty = 0;
        $shippingfee = 50;

        foreach ($items as $key => $value) {
            $sub_total += $value['quantity'] * $value['price'];
            $total_qty += $value['quantity'];

            Products::where('enable', 1)
              ->where('uuid', "$key")->decrement('quantity', $value['quantity']);
        }

        $total_price = $sub_total + $shippingfee;

        $cartResult = Carts::where('active', 1)
          ->where('customer_id', $request->userId)
          ->update(['active' => 0]);

        $orderResult = $details['user']->order()->create([
            'items' => json_encode($items),
            'customer_id' => $request->userId,
            'total_price' => $total_price,
            'total_quantity' => $total_qty,
            'number' => $request->number,
            'address' => $request->address,
            'buyer' => $request->fullname,
            'payment_method' => $request->method,
        ]);

        if ($orderResult) {
            return response()->json([
                'status' => 'success',
                'statusCode' => 'OrderSuccess',
                'message' => 'Successfully Ordered. Redirecting to home page'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'statusCode' => 'EncounteredError',
                'message' => 'Encounter an error. Please try again'
            ]);
        }
    }

    
    /**
     * get order list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrders(Request $request)
    {
        $result = Orders::all();
        return $this->processResponse($request->is('api/*'), ['orders' => $result], 'orders');
    }
    
    /**
     * get order list.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminGetAllOrders(Request $request)
    {
        $result = Orders::all();
        return $this->processResponse($request->is('api/*'), ['orders' => $result], 'orders');
    }

    /**
     * get current cart by user api.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrdersByUser(Request $request, $userId)
    {
        $user = User::find($userId);

        //check if existing 
        if (!$user) {
            return $this->emptyResultResponse('user');
        }

        $orders = Orders::where('customer_id', "$userId")
          ->get();

        //check if existing 
        if (!$orders) {
            return $this->emptyResultResponse('cart');
        }

        return $this->processResponse($request->is('api/*'), ['orders' => $orders], 'orders');
    }

    private function getCart($userId) {
        $user = User::find($userId);

        //check if existing 
        if (!$user) {
            $user = null;
        }

        $cart = Carts::where('active', 1)
          ->where('customer_id', "$userId")
          ->first();

        if (!$cart) {
            $cart = null;
        }

        return [
            'user' => $user,
            'cart' => $cart
        ];
    }

    private function emptyResultResponse ($type) {
        return response()->json([
            'status' => 'failed',
            'statusCode' => ucfirst($type) . 'NotExisting',
            'data' => null,
            'message' => ucfirst($type) . ' not found'
        ]);
    }

    /**
     * Check if request is from API or Web
     *
     */
    private function processResponse ($isApi, $data, $template = null)
    {
        if(!$isApi && ($template)) {
            return response()->view($template, $data);
        }

        return response()->json($data);
    }
}
