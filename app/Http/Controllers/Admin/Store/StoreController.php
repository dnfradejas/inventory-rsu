<?php

namespace App\Http\Controllers\Admin\Store;

use Exception;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRequest;

class StoreController extends Controller
{

    public function listing()
    {
        return view('admin.pages.store.listing', [
            'stores' => Store::get(),
        ]);
    }

    public function displayForm(string $slug = null)
    {
        $store = $slug ? Store::where('slug', $slug)->first() : new Store();
        return view('admin.pages.store.form', [
            'store' => $store,
            'cardTitle' => $slug ? 'Update store' : 'Add new store'
        ]);
    }
    
    /**
     * Create/update store
     *
     * @param \App\Http\Requests\Admin\StoreRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(StoreRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                $data = [
                    'store_name' => $request->store_name,
                    'status' => $request->status,
                    'address' => $request->address,
                    'slug' => sluggify($request->store_name) . uniqid(),
                    'tin' => $request->tin,
                    'telephone' => $request->telephone,
                ];

                Store::updateOrCreate([
                    'id' => $id
                ], $data);
            });
            return response()->json(api_response('Store successfully saved.'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving store!', 'error', 'Error', 400), 400);
        }
    }

    /**
     * Search store
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSearch(Request $request)
    {
        $stores = Store::where(function($query) use ($request) {
            if ($request->keyword) {
                return $query->orWhere('store_name', 'like', '%' . $request->keyword . '%')
                             ->orWhere('address', 'like', '%' . $request->keyword . '%');
            }
        })
        ->cursor();

        $html = view('admin.pages.store.search-results', ['stores' => $stores])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Delete store
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $this->assertNoAssociatedProduct($request);
            DB::table('product_store')->where('store_id', $request->id)->delete();
            Store::where('id', $request->id)->delete();
            return response()->json(api_response('Store has been deleted.'));
        } catch(Exception $e) {
            return response()->json(api_response($e->getMessage(), 'error', 'Error', 400), 400);
        }
    }

    protected function assertNoAssociatedProduct(Request $request)
    {
        $product = DB::table('product_store')
                     ->where('store_id', $request->id)
                     ->count();

        if ($product > 0) {
            throw new Exception("Cannot delete this store because there are products associated to this.");
        }
    }
}
