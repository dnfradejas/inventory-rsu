<?php

namespace App\Http\Controllers\Web;


use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{

    /**
     * @var \App\Models\Store
     */
    protected $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }


    /**
     * Display index page
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\View\View
     */
    public function getIndex(Request $request)
    {

        return redirect()->route('admin.get.secure.login');
        $storeId = session()->get(CURRENT_STORE);

        if ($storeId) {
            $viewData = [
                'stores' => $this->store->active()
                                 ->with(['products' => function($query){
                                    return $query->where('status', 'active');
                                 }])
                                 ->where('id', $storeId)
                                 ->first()
            ];
            
            return view('web.pages.shop.index', $viewData);
        }

        $viewData = [
            'stores' => $this->store->active()->get(),
        ];
        
        return view('welcome', $viewData);
    }

}
