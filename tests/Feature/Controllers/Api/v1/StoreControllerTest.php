<?php

namespace Tests\Feature\Controllers\Api\v1;

use Tests\TestCase;
use App\Models\Store;

class StoreControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Store::factory()->create();
    }

    public function testApiGetStoreList()
    {
        $response = $this->json('GET', route('api.v1.stores'));
        $this->assertSame('Odiongan Branch', $response->original[0]['store_name']);
    }

    public function testSearchStore()
    {
        $response = $this->json('GET', route('api.v1.stores', ['q' => 'Odiongan']));

        $this->assertSame('Odiongan Branch', $response->original[0]['store_name']);
    }
}