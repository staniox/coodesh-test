<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use MongoDB\BSON\ObjectId;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_creation()
    {
        $product = Product::factory()->create();
        $this->assertDatabaseHas('products', [
            '_id' => new ObjectId($product->_id)
        ]);
    }

    public function test_product_fillable_attributes()
    {
        $fillable = (new Product())->getFillable();
        $expected = [
            'code', 'status', 'imported_t', 'url', 'creator', 'created_t',
            'last_modified_t', 'product_name', 'quantity', 'brands', 'categories',
            'labels', 'cities', 'purchase_places', 'stores', 'ingredients_text',
            'traces', 'serving_size', 'serving_quantity', 'nutriscore_score',
            'nutriscore_grade', 'main_category', 'image_url'
        ];
        $this->assertEquals($expected, $fillable);
    }

    public function test_product_casts()
    {
        $casts = (new Product())->getCasts();
        $expected = [
            'imported_t' => 'datetime',
            'created_t' => 'integer',
            'last_modified_t' => 'integer',
            'serving_quantity' => 'float',
            'nutriscore_score' => 'integer',
        ];
        $this->assertEquals($expected, $casts);
    }
}
