<?php

namespace App\Http\Controllers\Admin\Supplier;

use Exception;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\DeliveryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    

    public function listing(Supplier $supplier)
    {
        return view('admin.pages.supplier.listing', [
            'suppliers' => $supplier->get(),
        ]);
    }



    /**
     * Display form
     *
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function displayForm($id = null)
    {
        $supplier = $id ? Supplier::find($id) : new Supplier();

        $view_data = [
            'supplier' => $supplier,
            'cardTitle' =>  $id ? 'Update brand' : 'Add new supplier'
        ];
        return view('admin.pages.supplier.form', $view_data);
    }


    /**
     * Create brand
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(Request $request)
    {

        $request->validate([
            'supplier' => 'required|unique:suppliers,name,' . $request->id,
        ]);
        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                Supplier::updateOrCreate([
                    'id' => $id,
                ], [
                    'name' => $request->supplier
                ]);
            });

            return response()->json(api_response('Supplier successfully saved.'), 201);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving supplier.', 400, 'failed', 'Failed'), 400);
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->assertNoProductAssociated($request);
            Supplier::where('id', $request->id)->delete();
            return response()->json(api_response('Supplier has been deleted.'));
        } catch(Exception $e) {
            return response()->json(api_response($e->getMessage(), 'error', 'Error', 400), 400);
        }
    }

    protected function assertNoProductAssociated(Request $request)
    {
        $product = DeliveryDetail::where('supplier_id', $request->id)->count();

        if ($product > 0) {
            throw new Exception("Cannot delete this suipplier because there are product deliveries associated to this.");
        }
    }
}
