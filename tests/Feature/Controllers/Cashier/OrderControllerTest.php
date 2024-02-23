<?php

namespace Tests\Feature\Controllers\Cashier;

use App\Models\Size;
use App\Models\Color;
use App\Models\Order;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ForOrder;
use App\Models\UnitOfMeasure;
use App\Models\OrderProduct;

class OrderControllerTest extends \Tests\TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Size::factory()->create();
        Color::factory()->create();
        Brand::factory()->create();
        Category::factory()->create();
        UnitOfMeasure::factory()->create();
        Store::factory()->create();

    }

    /**
     * Display input in modal when an item is click(cashier view)
     */
    public function testGetQuantityOfRecentlyAddedOrderProductAndDisplayInModal()
    {
        Product::factory()->create();

        ForOrder::factory()->create();

        $data = [
            'id' => 1,
            'store_id' => 1,
        ];

        $response = $this->json('POST', route('cashier.post.quantity.modal'), $data);

        $response->assertStatus(200);
    }

    public function testCreateProductOrder()
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, ['inventory_count' => 100]);
        
        $data = [
            'id' => 1,
            'quantity' => 10,
            'store' => 1,
        ];

        $this->json('POST', route('cashier.order.post.add.product'), $data);
        
        $this->assertDatabaseHas('for_orders', [
            'quantity' => 10,
        ]);
    }

    /**
     * Update the quantity of ordered plain product(no size and color)
     *
     */
    public function testUpdateQtyOfPlainOrderedProduct()
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, ['inventory_count' => 100]);
        ForOrder::factory()->create();

        $data = [
            'id' => 1,
            'quantity' => 11,
            'store' => 1
        ];

        
        $this->json('POST', route('cashier.order.post.add.product'), $data);

        $this->assertDatabaseHas('for_orders', [
            'quantity' => 11
        ]);

        $this->assertSame(1, ForOrder::count());

    }

    public function testCannotOrderInsufficientStock()
    {

        $product = Product::factory()->create();
        $product->sizes()->attach(1, ['inventory_count' => 100, 'price' => 600, 'discount_price' => 0]);
        ForOrder::factory()->create([
            'size_id' => 1,
        ]);

        $data = [
            'id' => 1,
            'quantity' => 500,
            'size' => 1,
            'store' => 1,
        ];

        $response = $this->json('POST', route('cashier.order.post.add.product'), $data);
        $this->assertSame('Insufficient stock.', $response->original['data']['results']);
        $response->assertStatus(403);
    }

    public function testDeleteForOrder()
    {
        $product = Product::factory()->create();
        ForOrder::factory()->create();
        $product->stores()->attach(1, ['inventory_count' => 100]);
        
        $this->json('DELETE', route('cashier.order.delete.product', ['id' => 1, 'store' => 1]));

        $this->assertSame(0, ForOrder::count());
    }

    /**
     * @dataProvider data
     */
    public function testCreateOrder($data)
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, ['inventory_count' => 100]);
        ForOrder::factory()->create([
            'size_id' => 1,
        ]);

        $this->json('POST', route('cashier.order.post.create'), $data);
        $this->assertDatabaseHas('product_store', [
            'inventory_count' => 90
        ]);
        
        $this->assertDatabaseHas('order_products', [
            'order_id' => 1,
            'color' => 'N/A',
            'size' => 'N/A',
            'final_price' => 599,
            'code' => 'code1'
        ]);

        $this->assertDatabaseHas('orders', [
            'grand_total' => 5990,
            'store_name' => 'Odiongan Branch'
        ]);
        
        $this->assertNull(ForOrder::first());

    }

    /**
     * @dataProvider data
     */
    public function testCreateOrderWithSize($data)
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, ['inventory_count' => 100]);
        ForOrder::factory()->create([
            'size_id' => 1,
        ]);

        // attach size
        $product->sizes()->attach(1, ['inventory_count' => 100, 'price' => 600, 'discount_price' => 0]);

        $this->json('POST', route('cashier.order.post.create'), $data);
        $this->assertDatabaseHas('product_size', [
            'inventory_count' => 90
        ]);
        
        $this->assertDatabaseHas('order_products', [
            'order_id' => 1,
            'color' => 'N/A',
            'size' => 'Small',
            'final_price' => 600
        ]);

        $this->assertDatabaseHas('orders', [
            'grand_total' => 6000
        ]);
        
        $this->assertNull(ForOrder::first());

    }


    public function testBarcodeCreateProductOrder()
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, ['inventory_count' => 20]);
        
        $data = [
            'quantity' => 10, // optional
            'store' => 1,
            'code' => 'code1',
        ];

        $this->json('POST', route('cashier.order.post.add.product'), $data);
        
        $this->assertDatabaseHas('for_orders', [
            'quantity' => 10
        ]);
    }

    public function data()
    {
        $data = [
            'sold_to' => 'John Doe',
            'status' => Order::ORDER_PROCESSING,
            'store' => 1,
        ];

        return [
            array($data)
        ];
    }
}