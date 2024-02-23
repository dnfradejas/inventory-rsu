<?php

namespace Tests\Feature\Controllers\Admin\UnitOfMeasure;

use Tests\TestCase;
use App\Models\UnitOfMeasure;

class UnitOfMeasureControllerTest extends TestCase
{
    

    /**
     * @dataProvider data
     */
    public function testCreateUnitOfMeasure(array $data)
    {
        $this->json('POST', route('admin.uom.post.create'), $data);
        $this->assertDatabaseHas('unit_of_measures', [
            'unit' => 'pcs'
        ]);
    }

    /**
     * @dataProvider data
     */
    public function testDeleteUom(array $data)
    {
        $this->json('POST', route('admin.uom.post.create'), $data);

        $response = $this->json('DELETE', route('admin.uom.delete'), ['id' => 1]);

        $this->assertNull(UnitOfMeasure::first());
        $this->assertEquals('Unit has been deleted.', $response->original['data']['results']);
    }

    public function data()
    {
        return [
            array(
                array(
                    'unit' => 'pcs',
                    // 'slug' => sluggify('pcs')
                )
            )
        ];
    }
}