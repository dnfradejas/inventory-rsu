<?php

namespace Tests\Feature\Controllers\Admin\Delivery;

use Tests\TestCase;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\UnitOfMeasure;
use App\Models\DeliveryDetail;


class DeliveryControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Brand::factory()->create();
        Category::factory()->create();
        UnitOfMeasure::factory()->create();
    }

    /**
     * @dataProvider data
     */
    public function testShouldAddProductDelivery(array $data)
    {
        $this->json('POST', route('admin.delivery.post.create'), $data);
        $this->assertDatabaseHas('delivery_details', [
            'quantity' => $data['quantity'],
        ]);
    }

    /** @dataProvider data */
    public function testShouldUpdateQuantityIfExpirationAndProductIsSame(array $data)
    {
        Product::factory()->create();
        $data['expiration_date'] = now()->__toString();
        DeliveryDetail::factory()->create([
            'expiration_date' => now()->__toString(),
        ]);

        $this->json('POST', route('admin.delivery.post.create'), $data);
        $this->assertDatabaseHas('delivery_details', [
            'quantity' => 30,
        ]);

    }

    public function testShouldReturnHtmlForDeliveryFormModal()
    {
        $response = $this->json('GET', route('admin.delivery.get.modal.html.form'));

        $this->assertNotNull($response->original['html']);
    }

    public function testDeleteExpiredProduct()
    {
        $request = [
            'id' => 1
        ];

        Product::factory()->create();
        $data['expiration_date'] = now()->__toString();
        DeliveryDetail::factory()->create([
            'expiration_date' => now()->subDay()->__toString(),
        ]);

        $response = $this->json('DELETE', route('admin.delivery.expired.delete'), $request);

        $this->assertSame('Expired product has been deleted.', $response->original['message']);
    }
    

    public function data()
    {
        $data = [
            'delivery_date' => '08-01-2022 12:40PM',
            'supplier' => 1,
            'product' => 1,
            'quantity' => 20,
            'barcode' => '1233',
            'production_date' => now()->__toString(),
            'expiration_date' => now()->addYear()->__toString(),
        ];

        return [
            array($data)
        ];
    }
}