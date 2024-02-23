<?php

namespace App\Http\Controllers\Admin\Brand;

use Exception;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;

class BrandController extends Controller
{
    

    /**
     * Display listing page
     *
     * @param \App\Models\Brand $brand
     * 
     * @return \Illuminate\View\View
     */
    public function listing(Brand $brand)
    {
        $view_data = [
            'brands' => $brand->get(),
        ];
        return view('admin.pages.brand.listing', $view_data);
    }



    /**
     * Display form
     *
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function displayForm($id = null)
    {
        $brand = $id ? Brand::find($id) : new Brand();

        $view_data = [
            'brand' => $brand,
            'cardTitle' =>  $id ? 'Update brand' : 'Add new brand'
        ];        
        return view('admin.pages.brand.form', $view_data);
    }


    /**
     * Create brand
     *
     * @param \App\Http\Requests\Admin\BrandRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(BrandRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                Brand::updateOrCreate([
                    'id' => $id,
                ], [
                    'brand' => $request->brand
                ]);
            });

            return response()->json(api_response('Brand successfully saved.'), 201);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving brand.', 400, 'failed', 'Failed'), 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->assertNoProductAssociated($request);
            Brand::where('id', $request->id)->delete();
            return response()->json(api_response('Brand has been deleted.'));
        } catch(Exception $e) {
            return response()->json(api_response($e->getMessage(), 'error', 'Error', 400), 400);
        }
    }

    protected function assertNoProductAssociated(Request $request)
    {
        $product = Product::where('brand_id', $request->id)->count();

        if ($product > 0) {
            throw new Exception("Cannot delete this brand because there are products associated to this.");
        }
    }
}
