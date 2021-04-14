<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Carts;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
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
     * get order list.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllOrders(Request $request)
    {
        $result = Orders::all();
        return $this->processResponse($request->is('api/*'), ['orders' => $result], 'orders');
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
            return response()->view('admin.'. $template, $data);
        }

        return response()->json($data);
    }
}
