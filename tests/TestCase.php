<?php

namespace Tests;

use App\Models\Product;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }


    protected function addItemToCart()
    {
        $product = Product::factory()->create();
        $product->stores()->attach(1, [
            'inventory_count' => 100,
        ]);

        $product = [
            'product' => 1,
            'store' => 1,
        ];
        $headers = $this->getGuestTokenHeader();
        
        $this->json('POST', route('api.v1.cart.post.add'), $product, $headers);
        return $headers;
    }


    protected function getGuestTokenHeader()
    {
        $response = $this->json('GET', route('api.v1.guest.token'));

        return [
            'Authorization' => 'Bearer ' . $response->original['token']
        ];
    }


    public function tearDown(): void
    {
        session()->forget(CURRENT_STORE);
        parent::tearDown();
    }
}
