<?php

namespace Tests\Feature\Controllers\Admin\Store;

use Tests\TestCase;
use App\Models\Store;

class StoreControllerTest extends TestCase
{

    /**
     * @dataProvider data
     */
    public function testCreateStore(array $data)
    {
        $this->json('POST', route('admin.store.post.create'), $data);
        $this->assertDatabaseHas('stores', $data);
    }

    /**
     * @dataProvider data
     */
    public function testShouldNotAllowSameStoreNameAndAddress(array $data)
    {
        Store::factory()->create();
        $data['address'] = 'Odiongan';

        $response = $this->json('POST', route('admin.store.post.create'), $data);
        $this->assertEquals('Store already exists in the same address.', $response->original['errors']['store_name'][0]);

        $data['address'] = 'Alcantara';

        $response = $this->json('POST', route('admin.store.post.create'), $data);
        $this->assertEquals('Store successfully saved.', $response->original['data']['results']);
    }

    public function testUpdateStore()
    {
        $store = Store::factory()->create();
        $data = [
            'id' => $store->id,
            'store_name' => 'San Andres',
            'address' => 'San Andres',
            'status' => Store::ACTIVE
        ];
        
        $this->json('POST', route('admin.store.post.create'), $data);
        $this->assertDatabaseHas('stores', $data);
    }

    public function testDeleteStore()
    {
        $store = Store::factory()->create();
        $data = [
            'id' => $store->id,
            'store_name' => 'San Andres',
            'address' => 'San Andres',
            'status' => Store::ACTIVE
        ];
        
        $response = $this->json('DELETE', route('admin.store.delete'), $data);
        $this->assertEquals('Store has been deleted.', $response->original['data']['results']);
    }


    public function data()
    {
        return [
            array(
                array(
                    'store_name' => 'Odiongan Branch',
                    'address' => 'Odiongan, Romblon',
                    'status' => Store::ACTIVE,
                    'tin' => '090-3039-3093-000',
                    'telephone' => '832-987893'
                )
            )
        ];
    }
}