<?php

namespace App\Http\Controllers\Admin\Color;

use Exception;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ColorRequest;

class ColorController extends Controller
{
    
    /**
     * Display listing page
     *
     * @param \App\Models\Color $color
     * 
     * @return \Illuminate\View\View
     */
    public function listing(Color $color)
    {
        $view_data = [
            'colors' => $color->get(),
        ];
        return view('admin.pages.color.listing', $view_data);
    }

    /**
     * Display form
     *
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function displayForm($id = null)
    {
        $color = $id ? Color::find($id) : new Color();

        $view_data = [
            'color' => $color,
            'cardTitle' =>  $id ? 'Update Color' : 'Add new color'
        ];        
        return view('admin.pages.color.form', $view_data);
    }

    /**
     * Create color
     * 
     * @param \App\Http\Requests\Admin\ColorRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(ColorRequest $request)
    {
        try {

            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;

                Color::updateOrCreate([
                    'id' => $id
                ], [
                    'color' => $request->color
                ]);
            });
            return response()->json(api_response('Color successfully saved.'), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error occured while saving color.', 'failed', 'Failed', 400), 400);
        }
    }

    /**
     * Delete color
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
                DB::table('color_product')
                  ->where('color_id', $id)
                  ->delete();

                Color::find($id)->delete();
            });

            return response()->json(api_response('Color successfully deleted.'), 200);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error occured while deleting color.', 'failed', 'Failed', 400), 400);
        }
    }
}
