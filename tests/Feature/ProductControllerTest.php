<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products()
    {
        Product::query()->delete();

        $products = Product::factory()->count(5)->create();

        $response = $this->getJson('/products');
        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertJsonFragment([
                'code' => $product->code,
                'status' => $product->status,
            ]);
        }
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $updateData = [
            'product_name' => 'Updated Product Name',
            'quantity' => 123,
            'status' => 'updated',
        ];

        $response = $this->putJson("/products/{$product->code}", $updateData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'code' => (string) $product->code,
            'product_name' => 'Updated Product Name',
            'quantity' => 123,
            'status' => 'updated',
        ]);
    }

    public function test_can_move_product_to_trash()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/products/{$product->code}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'code' => (string) $product->code,
            'status' => 'trash',
        ]);
    }

    public function test_can_view_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/products/{$product->code}");

        $response->assertStatus(200);

        $response->assertJson([
            'code' => (string) $product->code,
            'status' => $product->status,
            'imported_t' => $product->imported_t->toISOString(),
            'url' => $product->url,
            'creator' => $product->creator,
            'created_t' => $product->created_t,
            'last_modified_t' => $product->last_modified_t,
            'product_name' => $product->product_name,
            'quantity' => $product->quantity,
            'brands' => $product->brands,
            'categories' => $product->categories,
            'labels' => $product->labels,
            'cities' => $product->cities,
            'purchase_places' => $product->purchase_places,
            'stores' => $product->stores,
            'ingredients_text' => $product->ingredients_text,
            'traces' => $product->traces,
            'serving_size' => $product->serving_size,
            'serving_quantity' => $product->serving_quantity,
            'nutriscore_score' => $product->nutriscore_score,
            'nutriscore_grade' => $product->nutriscore_grade,
            'main_category' => $product->main_category,
            'image_url' => $product->image_url,
            'updated_at' => $product->updated_at->toISOString(),
            'created_at' => $product->created_at->toISOString(),
        ]);
    }
}
