<?php

namespace Tests\Feature\Controllers\Admin\Color;

use App\Models\Color;

class ColorControllerTest extends \Tests\TestCase
{


    /**
     * @dataProvider data
     */
    public function testCreateColor(array $data)
    {
        $response = $this->json('POST', route('admin.color.post.create'), $data);

        $this->assertEquals('Color successfully saved.',  $response->original['data']['results']);
        $this->assertDatabaseHas('colors', [
            'color' => 'Red'
        ]);
    }

    /**
     * @dataProvider data
     */
    public function testColorShouldBeUnique(array $data)
    {
        $color = Color::factory()->create([
            'color' => 'Red'
        ]);

        $response = $this->json('POST', route('admin.color.post.create'), $data);
        $this->assertSame([
            'message' => 'The given data was invalid.',
            'errors' => [
                'color' => [
                    'Color already exists.'
                ]
            ]
        ], $response->original);
    }

    public function testUpdateColor()
    {
        $color = Color::factory()->create();
        $data = [
            'id' => $color->id,
            'color' => 'Green'
        ];

        $response = $this->json('POST', route('admin.color.post.create'), $data);

        $this->assertEquals('Color successfully saved.',  $response->original['data']['results']);
        $this->assertDatabaseHas('colors', [
            'color' => 'Green'
        ]);

    }

    public function testDeleteColor()
    {
        $color = Color::factory()->create();
        $data = [
            'id' => $color->id,
        ];

        $response = $this->json('DELETE', route('admin.color.delete'), $data);

        $this->assertEquals('Color successfully deleted.',  $response->original['data']['results']);
        $this->assertNull(Color::first());
    }

    public function data()
    {
        $data = [
            'color' => 'Red'
        ];

        return [
            array($data)
        ];
    }
}