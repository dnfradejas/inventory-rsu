<?php

namespace Tests\Feature\Controllers\Admin\Dashboard;

use App\Models\Order;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\UnitOfMeasure;
use App\Models\DeliveryDetail;


class DashboardControllerTest extends \Tests\TestCase
{
 
    public function setUp(): void
    {
        parent::setUp();
        User::factory()->create();
        Brand::factory()->create();
        Category::factory()->create();
        UnitOfMeasure::factory()->create();
        Product::factory()->create();
        Order::factory()->create();
        OrderProduct::factory()->create();
        Supplier::factory()->create();
        DeliveryDetail::factory()->create();
    }

    public function testGetDashboardInfo()
    {
        $response = $this->json("GET", route('admin.dashboard.listing'));

        $responseData = $response->original;
        $this->assertEquals(1, $responseData['orders_count']);
        $this->assertEquals(1, $responseData['total_users']);
        $this->assertEquals('1,000.00', $responseData['total_sales']);

        $this->assertArrayHasKey('top_selling_products', $responseData);
        $this->assertArrayHasKey('weekly_sales_week_names', $responseData);
        $this->assertArrayHasKey('weekly_sales_week_values', $responseData);
    }
}