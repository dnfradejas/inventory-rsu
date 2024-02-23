<?php

namespace App\Http\Controllers\Cashier;

use stdClass;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\ForOrderRepository;

class CashierController extends Controller
{
    

    public function getIndex(
        Request $request,
        ProductRepository $productRepository,
        ForOrderRepository $forOrderRepository
    )
    {
        $store = Store::where('slug', $request->store)->first();
        
        if ($store) {
            $forOrders = $forOrderRepository->get($store->id);
            $orders = $forOrders['orders'];
            $grandTotal = $forOrders['grandTotal'];
            
            return view('cashier.index', [
                'products' => $productRepository->get($store),
                'orders' => $orders,
                'grandTotal' => number_format($grandTotal, 2),
                'store' => $store
            ]);

        }
        abort(404);
    }
}
