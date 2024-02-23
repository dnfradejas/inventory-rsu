<?php

namespace Tests\Feature\Controllers\Admin\Size;


use App\Models\Size;

class SizeControllerTest extends \Tests\TestCase
{

    /**
     * @dataProvider data
     */
    public function testCreateSize(array $data)
    {
        $response = $this->json('POST', route('admin.size.post.create'), $data);

        $this->assertSame('Size successfully saved.', $response->original['data']['results']);
        $this->assertDatabaseHas('sizes', [
            'size' => 'Small'
        ]);
    }

    /**
     * @dataProvider data
     */
    public function testUpdateSize(array $data)
    {
        $size = Size::factory()->create();
        $data['id'] = $size->id;
        $data['size'] = 'Large';

        $response = $this->json('POST', route('admin.size.post.create'), $data);

        $this->assertSame('Size successfully saved.', $response->original['data']['results']);
        $this->assertDatabaseHas('sizes', [
            'size' => 'Large'
        ]);
    }

    public function testDeleteSize()
    {
        $size = Size::factory()->create();
        $data = [
            'id' => $size->id
        ];
        $response = $this->json('DELETE', route('admin.size.delete'), $data);
        
        $this->assertSame('Size successfully deleted.', $response->original['data']['results']);
    }

    public function data()
    {
        $data = [
            'size' => 'Small'
        ];

        return [
            array($data)
        ];
    }
}