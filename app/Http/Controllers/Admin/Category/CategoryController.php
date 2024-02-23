<?php

namespace App\Http\Controllers\Admin\Category;

use Exception;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    

    /**
     * Display listing page
     *
     * @param \App\Models\Category $category
     * 
     * @return \Illuminate\View\View
     */
    public function listing(Category $category)
    {
        $view_data = [
            'categories' => $category->get(),
        ];
        return view('admin.pages.category.listing', $view_data);
    }

    /**
     * Display form
     *
     * @param int|null $id
     * @return \Illuminate\View\View
     */
    public function displayForm($id = null)
    {
        $category = $id ? Category::find($id) : new Category();

        $view_data = [
            'category' => $category,
            'cardTitle' =>  $id ? 'Update category' : 'Add new category'
        ];        
        return view('admin.pages.category.form', $view_data);
    }

    /**
     * Create category
     *
     * @param \App\Http\Requests\Admin\CategoryRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(CategoryRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $id = $request->has('id') && $request->id ? $request->id : null;
                Category::updateOrCreate([
                    'id' => $id
                ], [
                    'category' => $request->category
                ]);
            });
            return response()->json(api_response('Category successfully saved.'), 201);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(api_response('Error while saving category.', 'failed', 'Failed', 400), 400);
        }
    }

    /**
     * Delete category
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
        
        $id = $request->id;

        $hasProduct = Product::where('category_id', $id)->exists();
        if (!$hasProduct) {
            try {
                DB::transaction(function() use ($id) {
                    Category::find($id)->delete();
                });
                return response()->json(api_response('Category successfully deleted.'), 200);
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return response()->json(api_response('Error occurred while deleting category.', 'failed', 'Failed', 400), 400);
            }
        }
        return response()->json(api_response('Oops! Category has linked products. Please delete product linked to this category before you can delete this.', 'failed', 'Failed', 400), 400);
    }
}
