<?php

namespace App\Http\Controllers\Admin\Delivery;

use Exception;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\DeliveryDetail;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeliveryRequest;

class DeliveryController extends Controller
{

    /**
     * @var \App\Models\Product
     */
    protected $product;

    /**
     * @var \App\Models\Supplier
     */
    protected $supplier;


    public function __construct(Product $product, Supplier $supplier)
    {
        $this->product = $product;
        $this->supplier = $supplier;
    }


    /**
     * Get listing
     *
     * @return \Illuminate\View\View
     */
    public function getListing()
    {
        $details = $this->getDeliveryDetails();
        
        $viewData = [
            'details' => $details,
        ];
        
        return view('admin.pages.delivery.listing', $viewData);
    }

    /**
     * Get html to be displayed in modal
     *
     * @param string|int|null $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHtmlModalForm($id = null)
    {
        $detail = new \stdClass();
        $detail->id = null;
        $detail->barcode = null;
        $detail->delivery_date = null;
        $detail->quantity = null;
        $detail->production_date = null;
        $detail->expiration_date = null;
        $detail->suppier_id = null;
        $detail->product_id = null;

        if ($id) {
            $detail = $this->getDeliveryDetails($id);
        }

        $viewData = [
            'id' => $id,
            'detail' => $detail,
            'products' => $this->product->where('status', Product::ACTIVE)->get(),
            'suppliers' => $this->supplier->get(),
        ];

        $html = view('admin.pages.delivery.html-modal-form', $viewData)->render();

        return response()->json(['html' => $html]);
    }
    
    /**
     * Add product delivery details
     *
     * @param \App\Http\Requests\Admin\DeliveryRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postAddDelivery(DeliveryRequest $request)
    {
        try {

            DB::transaction(function() use ($request) {

                $data = [
                    'product_id' => $request->product,
                    'supplier_id' => $request->supplier,
                    'quantity' => $request->quantity,
                    'barcode' => $request->barcode,
                    'delivery_date' => $request->delivery_date,
                    'production_date' => $request->production_date,
                    'expiration_date' => $request->expiration_date,
                ];
                $product = Product::find($request->product);
                if ($request->has('id') && $request->id) {
                    $detail = DeliveryDetail::whereId($request->id)->first();
                    $changed = [];
                    if ($detail->quantity != $request->quantity) {
                        $changed[] = sprintf("from quantity %d to %d", $detail->quantity, $request->quantity); 
                    }
                    if ($detail->delivery_date != $request->delivery_date) {
                        $changed[] = sprintf("from delivery date %s to %s", $detail->delivery_date, $request->delivery_date); 
                    }
                    if ($detail->production_date != $request->production_date) {
                        $changed[] = sprintf("from production date %s to %s", $detail->production_date, $request->production_date); 
                    }
                    if ($detail->expiration_date != $request->expiration_date) {
                        $changed[] = sprintf("from expiration date %s to %s", $detail->expiration_date, $request->expiration_date); 
                    }
                    
                    DeliveryDetail::whereId($request->id)->update($data);
                    if (count($changed) > 0) {
                        audit_log(sprintf("%s updated delivery details of product %s with id %d %s", admin()->fullname, $product->product_name, $product->id, implode(', ', $changed)));
                    }
                } else {
                    $find = DeliveryDetail::where(function($query) use ($request) {
                                                $query->where('product_id', $request->product)
                                                      ->where('expiration_date', $request->expiration_date);
                                                if ($request->barcode) {
                                                    $query->where('barcode', $request->barcode);
                                                }
                                          })
                                          ->first();

                    
                    if ($find) {
                        $quantity = $data['quantity'] + $find->quantity;
                        $find->quantity = $quantity;
                        $find->updated_at = now()->__toString();
                        $find->save();
                        audit_log(sprintf("%s updated delivery details quantity of product %s with id %d from %d to %d", admin()->fullname, $product->product_name, $product->id, $find->quantity, $quantity));
                    } else {
                        DeliveryDetail::create($data);
                        audit_log(sprintf("%s add new delivery details of product %s with id %d", admin()->fullname, $product->product_name, $product->id));
                    }
                }
            });

            return response()->json(['message' => 'Product delivery details saved!']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Oops! Something went wrong!', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteExpired(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        DeliveryDetail::find($request->id)->delete();
        
        return response()->json(['message' => 'Expired product has been deleted.']);
    }
}
