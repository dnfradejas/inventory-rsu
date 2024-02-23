<?php


namespace Tests\Feature\Controllers\Admin\Order;

use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\UnitOfMeasure;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\DeliveryDetail;

class OrderControllerTest extends TestCase
{
    protected $orderRef;

    public function setUp(): void
    {
        parent::setUp();
        $this->orderRef = reference_number();
        UnitOfMeasure::factory()->create();
        Category::factory()->create();
        Brand::factory()->create();
        Product::factory()->create();
        Supplier::factory()->create();
    }



    public function testScanBarcodeToOrder()
    {
        DeliveryDetail::factory()->create();

        $data = [
            'quantity' => 1,
            'barcode' => '123'
        ];
        $response = $this->json('POST', route('admin.order.post.add'), $data);
        $this->assertArrayHasKey('html', $response->original);
    }

    public function testUpdateOrderQuantityOfSameBarcode()
    {
        DeliveryDetail::factory()->create();
        Order::factory()->create();
        OrderProduct::factory()->create();

        $data = [
            'quantity' => 10,
            'barcode' => '123'
        ];
        $response = $this->json('POST', route('admin.order.post.add'), $data);
        $this->assertArrayHasKey('html', $response->original);
        $this->assertDatabaseHas('order_products', [
            'quantity' => 10,
        ]);
    }

    public function testUpdateOrderQuantityBasedOnDeliveryId()
    {
        DeliveryDetail::factory()->create();
        Order::factory()->create();
        OrderProduct::factory()->create();

        $data = [
            'quantity' => 10,
            'id' => 1,
            'quantity_append' => true
        ];

        $response = $this->json('POST', route('admin.order.post.add'), $data);
        $this->assertArrayHasKey('html', $response->original);
        $this->assertDatabaseHas('order_products', [
            'quantity' => 12,
        ]);
    }

    public function testUpdateOrderQuantityBasedOnOrderProductId()
    {
        DeliveryDetail::factory()->create();
        Order::factory()->create([
            'status' => Order::ORDER_DRAFT
        ]);
        OrderProduct::factory()->create();
        
        $data = [
            'quantity' => 10,
            'order_product_id' => 1,
            'quantity_append' => false
        ];

        $response = $this->json('POST', route('admin.order.post.add'), $data);
        
        $this->assertArrayHasKey('html', $response->original);
        $this->assertDatabaseHas('order_products', [
            'quantity' => 10,
        ]);
    }


    public function testAddProductToOrderWhenClickOnIndividualProductListItem()
    {
        DeliveryDetail::factory()->create();

        $data = [
            'id' => 1,
            'delivery_detail_id' => 1,
            'quantity' => 1,

        ];
        $response = $this->json('POST', route('admin.order.post.add'), $data);
        $this->assertArrayHasKey('html', $response->original);
    }

    public function testShouldRemoveOrderItemFromList()
    {

        $data = [
            'id' => 1
        ];
        
        DeliveryDetail::factory()->create();
        Order::factory()->create();
        OrderProduct::factory()->create();

        $response = $this->json('POST', route('admin.order.post.delete.item'), $data);

        $this->assertArrayHasKey('html', $response->original);
        $this->assertDatabaseMissing('order_products', [
            'quantity' => 10,
        ]);
    }


    public function testCreateNewOrderWithSameProduct()
    {
        DeliveryDetail::factory()->create();
        Order::factory()->create([
            'status' => Order::ORDER_PROCESSING
        ]);
        OrderProduct::factory()->create();

        $data = [
            'quantity' => 10,
            'id' => 1,
        ];
        
        $response = $this->json('POST', route('admin.order.post.add'), $data);
        
        $this->assertArrayHasKey('html', $response->original);
        $this->assertEquals(2, OrderProduct::count());
    }


    public function testShouldNotAllowNoStockItemToOrder()
    {
        DeliveryDetail::factory()->create([
            'quantity' => 0
        ]);

        $data = [
            'quantity' => 10,
            'id' => 1,
        ];
        
        $response = $this->json('POST', route('admin.order.post.add'), $data);

        $response->assertStatus(403);
        $this->assertEquals("No stock available for product Product 1", $response->original['message']);
    }


    public function testGetOrderList()
    {
        Order::factory()->create([
            'order_ref' => $this->orderRef
        ]);
        OrderProduct::factory()->create();
        $response = $this->json('GET', route('admin.order.listing'));
        
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $response->original['data']['results']);
    }

    public function testViewOrderDetails()
    {
        Order::factory()->create([
            'order_ref' => $this->orderRef
        ]);
        OrderProduct::factory()->create();

        $routeParam = [
            'order_ref' => $this->orderRef
        ];
        $response = $this->json('GET', route('admin.order.view.detail', $routeParam));
        
        $this->assertArrayHasKey('orderInfo', $response->original['data']['results']);
    }

    public function testAdminCancelOrder()
    {
        Order::factory()->create([
            'order_ref' => $this->orderRef,
            'status' => Order::ORDER_PROCESSING
        ]);
        OrderProduct::factory()->create();
        DeliveryDetail::factory()->create();
        $this->json('POST', route('admin.order.post.cancel', ['id' => 1]));
        
        $this->assertDatabaseHas('orders', [
            'status' => Order::ORDER_CANCELLED
        ]);
    }

    public function testDontAllowOrderCancelIfPaid()
    {
        Order::factory()->create([
            'order_ref' => $this->orderRef,
        ]);

        OrderProduct::factory()->create();
        $response = $this->json('POST', route('admin.order.post.cancel', ['id' => 1]));
        $response->assertStatus(400);

        $this->assertEquals('Order cannot be cancelled.', $response->original['data']['results']);
    }


    public function testAdminExportOrders()
    {

        Order::factory()->create([
            'order_ref' => $this->orderRef,
            'status' => Order::ORDER_PROCESSING
        ]);
        OrderProduct::factory()->create();
        $filter = [
            'date' => '02/07/2020 - 02/07/2022',
            'status' => Order::ORDER_PROCESSING
        ];

        $response = $this->json('POST', route('admin.order.post.export'), $filter);

        $this->assertSame('PHP500', $response->original[0][8]);
    }

    public function testUpdateOrderStatus()
    {

        Order::factory()->create([
            'order_ref' => $this->orderRef,
            'status' => Order::ORDER_PROCESSING
        ]);
        OrderProduct::factory()->create();

        $response = $this->json('POST', route('admin.order.update.status'), ['id' => 1, 'status' => Order::ORDER_CANCELLED]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'status' => Order::ORDER_CANCELLED,
        ]);
    }
}