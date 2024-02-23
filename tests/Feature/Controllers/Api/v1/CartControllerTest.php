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

class CartControllerTest extends TestCase
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

    

    public function testAddToCart()
    {
        $this->addItemToCart();
        
        $this->assertDatabaseHas('cart', [
            'quantity' => 1,
            'product_id' => 1,
        ]);
    }

    

    public function testSubVariantMustBeSelected()
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, [
            'inventory_count' => 100,
        ]);

        $product->sizes()->attach(1, [
            'inventory_count' => 100,
            'price' => 500,
            'discount_price' => 0,
        ]);

        $product->colors()->attach(1);

        $product = [
            'product' => 1,
            'size' => null,
            'color' => null,
            'store' => 1
        ];
        $headers = $this->getGuestTokenHeader();
        $response = $this->json('POST', route('api.v1.cart.post.add'), $product, $headers);
        $this->assertSame('Please select size.', $response->original['message']);
    }

    public function testAddToCartWithVariant()
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, [
            'inventory_count' => 100,
        ]);
        $product->sizes()->attach(1, [
            'inventory_count' => 100,
            'price' => 500,
            'discount_price' => 0,
        ]);

        $product->colors()->attach(1);

        $data = [
            'product' => 1,
            'size' => 1,
            'color' => 1,
            'store' => 1
        ];

        $headers = $this->getGuestTokenHeader();
        
        $response = $this->json('POST', route('api.v1.cart.post.add'), $data, $headers);
        
        $this->assertDatabaseHas('cart', [
            'quantity' => 1,
        ]);

        // another variant
        $color = Color::factory()->create();

        $product->colors()->attach($color->id);

        $data = [
            'product' => 1,
            'size' => 1,
            'color' => $color->id,
            'store' => 1
        ];

        $this->json('POST', route('api.v1.cart.post.add'), $data);

        $this->assertDatabaseHas('cart', [
            'color_id' => 2,
        ]);
    }

    public function testDeleteProductFromCart()
    {
        $this->addItemToCart();
        $product = [
            'id' => 1,
            'store' => 1,
        ];
        $headers = $this->getGuestTokenHeader();
        $response = $this->json('POST', route('api.v1.cart.post.delete'), $product, $headers);
        $this->assertTrue(count($response->original['products']) === 0);
        $this->assertNull(Cart::first());
    }

    public function testGetCartItems()
    {
        $headers = $this->addItemToCart();
        $response = $this->json('GET', route('api.v1.cart.get', ['store' => 1]), [], $headers);
        
        $this->assertArrayHasKey('products', $response->original);
        $this->assertEquals(599, $response->original['grand_total']);
    }
}