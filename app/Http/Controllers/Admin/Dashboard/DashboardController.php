<?php

namespace App\Http\Controllers\Admin\Dashboard;

use DateTime;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Models\Repositories\OrderRepository;

class DashboardController extends Controller
{
    
    /**
     * @var \App\Models\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * @var \App\Models\Product
     */
    protected $product;

    /**
     * @var \App\Models\Supplier
     */
    protected $supplier;


    public function __construct(OrderRepository $orderRepository, Product $product, Supplier $supplier)
    {
        $this->orderRepository = $orderRepository;
        $this->product = $product;
        $this->supplier = $supplier;
    }

    

    /**
     * Display dashboard listing page
     *
     * @return \Illuminate\View\View
     */
    public function listing()
    {
        
        $details = $this->getDeliveryDetails();
        list($expiringProducts, $lowStocks) = $this->getProductDetails($details);
        list($ordersCount, $totalSales, $topSellingProducts, $companySales) = $this->orderRepository->orders();

        $viewData = [
            'orders_count' => $ordersCount,
            'total_users'  => User::count(),
            'expiringProducts' => $expiringProducts,
            'lowStocks' => $lowStocks,
            'total_sales' => number_format($totalSales, 2),
            'top_selling_products' => $topSellingProducts,
            'company_sales_name' => array_keys($companySales),
            'company_sales_name_values' => array_values($companySales),
        ];
        if (is_testing()) {
            return response()->json($viewData);
        }
        return view('admin.pages.dashboard.listing', $viewData);
    }

    /**
     * Get expiring products
     * 
     * @param \Illuminate\Support\Collection $products
     *
     * @return array<int, array>
     */
    protected function getProductDetails(Collection $products)
    {
        $expirating = [];
        $stocks = [];
        $now = new DateTime();
        foreach($products as $product){
            
            $date2 = new DateTime($product->expiration_date);
            $interval = $now->diff($date2);

            if ($interval->days <= 10) {
                array_push($expirating, $product);
            }

            if (isset($stocks[$product->product_id])) {
                $stocks[$product->product_id]->quantity += $product->quantity;
            } else {
                $stocks[$product->product_id] = $product;
            }
        }
        
        $lowStocks = [];
        foreach($stocks as $key => $lowStock) {
            if ($lowStock->quantity <= 50) {
                array_push($lowStocks, $lowStock);
            }
        }
        $lowStocks = array_values($lowStocks);

        return [$expirating, $lowStocks];
    }
}
