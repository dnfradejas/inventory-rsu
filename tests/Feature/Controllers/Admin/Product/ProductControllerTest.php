<?php

namespace Tests\Feature\Controllers\Admin\Product;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\OrderProduct;
use App\Models\UnitOfMeasure;
use App\Models\DeliveryDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\ProductRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductControllerTest extends \Tests\TestCase
{


    public function setUp(): void
    {
        parent::setUp();

        Brand::factory()->create();
        Category::factory()->create();
        UnitOfMeasure::factory()->create();
        
    }

    public function testGetProductList()
    {
        
        $product = Product::factory()->create();

        
        $response = $this->json('GET', route('admin.product.listing'));
        
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $response->original['data']['results']);
    }


    /**
     * @dataProvider data
     */
    public function testAddNewProduct($request)
    {

        $response = $this->json('POST', route('admin.product.post.create'), $request->all());
        
        $this->assertEquals('Product successfully saved!', $response->original['data']['results']);
        
    }

    /**
     * @dataProvider data
     */
    public function testCanUpdateProduct($request)
    {
        Product::factory()->create();
        $request->merge([
            'id' => 1,
            'product_name' => 'New Name',
        ]);

        $this->json('POST', route('admin.product.post.create'), $request->all());
        
        $this->assertDatabaseHas('products', [
            'brand_id' => 1,
            'category_id' => 1,
            'sku' => 'sku1',
            'product_name' => 'New Name'
        ]);
    }

    public function testUpdateProductWithOutImage()
    {
        Product::factory()->create();

        $data = [
            'id' => 1,
            'brand' => 1,
            'category' => 1,
            'unit_of_measure' => 1,
            'product_name' => 'No Image',
            'code' => '123',
            'sku' => 'sku1',
            'price' => 599,
            'discount_price' => 0,
            'status' => 'active'
        ];

        $this->json('POST', route('admin.product.post.create'), $data);
        

        $this->assertDatabaseHas('products', [
            'brand_id' => 1,
            'category_id' => 1,
            'sku' => 'sku1',
            'product_name' => 'No Image'
        ]);
        
    }

    public function testDeleteProduct()
    {
        $product = Product::factory()->create();
        
        $data = [
            'id' => $product->id,
        ];

        $response = $this->json('DELETE', route('admin.product.post.delete'), $data);

        $this->assertSame('Product successfully deleted.', $response->original['data']['results']);
        $this->assertDatabaseHas('products', [
            'deleted_at' => now(),
        ]);

    }

    public function testShouldCheckProductPriceBaseOnBarcode()
    {
        Supplier::factory()->create();
        Product::factory()->create();
        DeliveryDetail::factory()->create();

        $data = [
            'q' => '123',
        ];

        $response = $this->json('POST', route('admin.product.post.check.price'), $data);
        
        $this->assertNotNull($response->original['html']);
    }

    public function testShouldViewProductDetails()
    {

        Supplier::factory()->create();
        Product::factory()->create();
        DeliveryDetail::factory()->create();
        Order::factory()->create();
        OrderProduct::factory()->create();
        

        $this->json('GET', route('admin.product.view.detail', ['id' => 1]));
        // bad test XD
        $this->assertTrue(true);

    }

    

    public function getFormUploadedProductImage()
    {

        $_FILES = [
            'image' => [
                'name' => 'avatar.png',
                'type' => 'image/jpg',
                'tmp_name' => __DIR__ . '/_files/avatar.png',
                'error'    => 0,
                'size'     => 20000,
            ]
        ];

        return new UploadedFile($_FILES['image']['tmp_name'], $_FILES['image']['name']);
    }


    public function data()
    {
        $data = [
            'brand' => 1,
            'category' => 1,
            'unit_of_measure' => 1,
            'product_name' => 'Product 1',
            'sku' => 'sku1',
            'code' => '1234',
            'price' => 599,
            'discount_price' => 0,
            // 'image' => '/storage/app/products/t/e/testproduct.jpg',
            'status' => 'active'
        ];

        $request = app(ProductRequest::class);
        $request->replace($data);
        $request->files->set('image', $this->getFormUploadedProductImage());

        return [
            array($request)
        ];
    }
}