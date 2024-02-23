<?php

namespace Tests\Feature\Controllers\Admin\Category;

use App\Models\Category;
class CategoryControllerTest extends \Tests\TestCase
{


    /**
     * @dataProvider data
     */
    public function testCreateCategory(array $data)
    {
        $this->json('POST', route('admin.category.post.create'), $data);
        $this->assertDatabaseHas('categories', [
            'category' => 'Category1'
        ]);
    }

    public function testUpdateCategory()
    {
        $category = Category::factory()->create();
        $data = [
            'id' => $category->id,
            'category' => 'New Category'
        ];

        $this->json('POST', route('admin.category.post.create'), $data);
        $this->assertDatabaseHas('categories', [
            'category' => 'New Category'
        ]);
    }

    /**
     * @dataProvider data
     */
    public function testCategoryShouldBeUnique(array $data)
    {
        Category::factory()->create();
        $response = $this->json('POST', route('admin.category.post.create'), $data);
        
        $this->assertEquals([
            'message' => 'The given data was invalid.',
            'errors' => [
                'category' => [
                    'Category already exists.'
                ]
            ]
        ], $response->original);
    }

    public function testDeleteCategory()
    {
        Category::factory()->create();
        $this->json('DELETE', route('admin.category.delete'), ['id' => 1]);
        $this->assertNull(Category::first());
    }


    public function data()
    {
        $data = [
            'category' => 'Category1'
        ];

        return [
            array($data)
        ];
    }

}
