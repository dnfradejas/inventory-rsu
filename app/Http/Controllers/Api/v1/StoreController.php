<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StoreCollection;

class StoreController extends Controller
{
    

    /**
     * Search callback
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Closure
     */
    protected function searchCallback(Request $request)
    {

        return function($query) use ($request) {
            if ($request->has('q') && $request->q) {
                return $query->orWhere('store_name', 'like', '%' . $request->q . '%')
                             ->orWhere('address', 'like', '%' . $request->q . '%');
            }  
        };
    }
    
    /**
     * Get list of stores
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStoreList(Request $request)
    {
        $stores = Store::active()
                       ->where($this->searchCallback($request))
                       ->get();

        

        $data = [];
        foreach($stores as $store){
            $data[] = [
                'id' => $store->id,
                'store_name' => $store->store_name,
                'address' => $store->address,
                'tin' => $store->tin,
                'telephone' => $store->telephone,
                'image_url' => url('/storage/images/icon/store-logo.png'),
            ];
        }

        return response()->json($data);
    }
}
