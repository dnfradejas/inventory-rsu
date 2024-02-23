<?php

namespace App\Http\Controllers\Admin\Size;

use Exception;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SizeRequest;

class SizeController extends Controller
{
    

    /**
     * Display listing page
     *
     * @param \App\Models\Size $size
     * 
     * @return \Illuminate\View\View
     */
    public function listing(Size $size)
    {
        $view_data = [
            'sizes' => $size->get(),
        ];
        return view('admin.pages.size.listing', $view_data);
    }

    /**
     * Create size
     *
     * @param \App\Http\Requests\Admin\SizeRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(SizeRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                Size::updateOrCreate([
                    'id' => $id
                ], [
                    'size' => $request->size
                ]);
            });
            return response()->json(api_response('Size successfully saved.'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving size.', 'failed', 'Failed', 400), 400);
        }
    }

    /**
     * Display form
     *
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function displayForm($id = null)
    {
        $size = $id ? Size::find($id) : new Size();

        $view_data = [
            'size' => $size,
            'cardTitle' =>  $id ? 'Update size' : 'Add new size'
        ];        
        return view('admin.pages.size.form', $view_data);
    }

    /**
     * Delete size
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            DB::transaction(function() use ($request) {
                $id = $request->id;
                DB::table('product_size')
                  ->where('size_id', $id)
                  ->delete();
                Size::where('id', $id)->delete();
            });
            return response()->json(api_response('Size successfully deleted.'), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error occured while deleting size.', 'failed', 'Failed', 400), 400);
        }
    }
}
