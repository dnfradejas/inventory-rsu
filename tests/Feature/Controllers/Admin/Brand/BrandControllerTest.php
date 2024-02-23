<?php

namespace Tests\Feature\Controllers\Admin\Brand;

use App\Models\Brand;

class BrandControllerTest extends \Tests\TestCase
{


    /**
     * @dataProvider data
     */
    public function testCreateBrand(array $data)
    {
        $this->json('POST', route('admin.brand.post.create'), $data);
        $this->assertDatabaseHas('brands', [
            'brand' => 'Brand1'
        ]);
    }

    public function testUpdateBrand()
    {
        $brand = Brand::factory()->create();
        $data = [
            'id' => $brand->id,
            'brand' => 'New Brand'
        ];

        $this->json('POST', route('admin.brand.post.create'), $data);
        $this->assertDatabaseHas('brands', [
            'brand' => 'New Brand'
        ]);
    }

    /**
     * @dataProvider data
     */
    public function testShouldBrandShouldBeUnique(array $data)
    {
        Brand::factory()->create();
        $response = $this->json('POST', route('admin.brand.post.create'), $data);

        $this->assertEquals([
            'message' => 'The given data was invalid.',
            'errors' => [
                'brand' => [
                    'The brand already exists.'
                ]
            ]
        ], $response->original);
    }

    public function testDeleteBrand()
    {
        Brand::factory()->create();

        $response = $this->json('DELETE', route('admin.brand.delete'), ['id' => 1]);
        $this->assertEquals('Brand has been deleted.', $response->original['data']['results']);
    }
    
    public function data()
    {
        $data = [
            'brand' => 'Brand1'
        ];

        return [
            array($data)
        ];
    }
}