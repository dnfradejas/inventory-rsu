<?php

namespace Tests\Feature\Controllers\Api\v1;

use Tests\TestCase;
use App\Models\Size;
use App\Models\Color;
use App\Models\Store;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\UnitOfMeasure;

class ProductControllerTest extends TestCase
{


    public function setUp(): void
    {
        parent::setUp();
        Category::factory()->create();
        Brand::factory()->create();
        Size::factory()->create();
        Size::factory()->create([
            'size' => 'XL'
        ]);
        Color::factory()->create();
        Color::factory()->create([
            'color' => 'Green'
        ]);
        Store::factory()->create();
        UnitOfMeasure::factory()->create();

        $product = Product::factory()->create();
        $product->stores()->attach(1, [
            'inventory_count' => 100,
        ]);

        $sizeDetails = ['inventory_count' => 10, 'price' => 100];

        $product->sizes()->attach([1 => $sizeDetails, 2 => $sizeDetails]);

        $product->colors()->attach([1, 2]);

        $product = Product::factory()->create([
            'product_name' => 'Product 2',
            'slug' => 'product-2'
        ]);
        $product->stores()->attach(1, [
            'inventory_count' => 100,
        ]);

        $sizeDetails = ['inventory_count' => 10, 'price' => 100];

        $product->sizes()->attach([1 => $sizeDetails, 2 => $sizeDetails]);

        $product->colors()->attach([2]);

    }

    public function testRetrieveProductList()
    {
        $routeParams = [
            'store_id' => 1
        ];
        $response = $this->json('GET', route('api.v1.products', $routeParams));

        $this->assertSame('Product 1', $response->original['products'][0]['product_name']);
    }

    public function testSearchProduct()
    {
        $routeParams = [
            'store_id' => 1,
            'q' => 'Product'
        ];
        $response = $this->json('GET', route('api.v1.products', $routeParams));
        $this->assertSame('Product 1', $response->original['products'][0]['product_name']);
    }

    public function testRetrieveProductDetail()
    {
        $routeParams = [
            'store_id' => 1,
            'slug' => 'product-2'
        ];
        $response = $this->json('GET', route('api.v1.products.view.detail', $routeParams));
        
        $this->assertArrayHasKey('product', $response->original);
    }
}