<?php

namespace App\Http\Controllers\Admin\UnitOfMeasure;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\UnitOfMeasure;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class UnitOfMeasureController extends Controller
{
    

    public function listing()
    {
        $view_data = [
            'uoms' => UnitOfMeasure::get(),
        ];
        return view('admin.pages.uom.listing', $view_data);
    }

    /**
     * Disply form
     * 
     * @param string|null slug
     *
     * @return \Illuminate\View\View
     */
    public function displayForm(string $slug =  null)
    {
        $uom = $slug ? UnitOfMeasure::where('slug', $slug)->first() : new UnitOfMeasure();
        return view('admin.pages.uom.form', [
            'cardTitle' => $slug ? 'Update Unit of Measure' : 'Add Unit of Measures',
            'uom' => $uom
        ]);
    }

    /**
     * Create/update
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(Request $request)
    {
        $request->validate([
            'unit' => [
                'required',
                Rule::unique('unit_of_measures')->ignore($request->id)
            ]
        ]);

        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                UnitOfMeasure::updateOrCreate([
                    'id' => $id
                ], [
                    'unit' => $request->unit,
                    'slug' => sluggify($request->unit),
                ]);
            });
            return response()->json(api_response('Unit of measure saved!'));
        } catch (Exception $e) {
            return response()->json(api_response('Error while saving unit of measure!', 'error', 'Error', 400), 400);

        }
    }

    /**
     * Delete uom
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $this->assertNoUomAssociated($request);

            $id = $request->id;
            UnitOfMeasure::where('id', $id)->delete();
            return response()->json(api_response('Unit has been deleted.'));
        } catch (Exception $e) {
            return response()->json(api_response($e->getMessage(), 'error', 'Error', 400), 400);
        }
    }

    private function assertNoUomAssociated(Request $request)
    {
        $product = Product::where('unit_of_measure_id', $request->id)->count();

        if ($product > 0) {
            throw new Exception("Cannot delete this unit because there are products associated to this.");
        }
        
    }
}
