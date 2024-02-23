<?php

namespace Tests\Feature\Controllers\Api\v1;

use Tests\TestCase;
use App\Models\Size;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\UnitOfMeasure;

class PlaceOrderControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Category::factory()->create();
        Brand::factory()->create();
        Size::factory()->create();
        Color::factory()->create();
        Store::factory()->create();
        UnitOfMeasure::factory()->create();

    }

    /**
     * @dataProvider data
     */
    public function testPlaceOrder(array $data)
    {
        $headers = $this->addItemToCart();

        $response = $this->json('POST', route('api.v1.place.order'), $data, $headers);

        $this->assertEquals('Order has been placed.', $response->original['message']);
        $this->assertDatabaseHas('order_products', [
            'product_name' => 'Product 1'
        ]);
    }

    public function data()
    {
        $data = [
            'store' => 1,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'jdoe@example.com',
            'mobile_number' => '09455689856',
        ];

        return [
            array($data)
        ];
    }
}