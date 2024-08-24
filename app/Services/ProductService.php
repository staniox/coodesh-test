<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function createProducts(array $productsData): void
    {
        foreach ($productsData as $productData) {
            try {
                $filteredData = [
                    'code' => str_replace('\\"', '', $productData['code']),
                    'status' => $productData['status'] ?? 'draft',
                    'imported_t' => $productData['imported_t'] ?? now(),
                    'url' => $productData['url'] ?? null,
                    'creator' => $productData['creator'] ?? null,
                    'created_t' => $productData['created_t'] ?? null,
                    'last_modified_t' => $productData['last_modified_t'] ?? null,
                    'product_name' => $productData['product_name'] ?? null,
                    'quantity' => $productData['quantity'] ?? null,
                    'brands' => $productData['brands'] ?? null,
                    'categories' => $productData['categories'] ?? null,
                    'labels' => $productData['labels'] ?? null,
                    'cities' => $productData['cities'] ?? null,
                    'purchase_places' => $productData['purchase_places'] ?? null,
                    'stores' => $productData['stores'] ?? null,
                    'ingredients_text' => $productData['ingredients_text'] ?? null,
                    'traces' => $productData['traces'] ?? null,
                    'serving_size' => $productData['serving_size'] ?? null,
                    'serving_quantity' => $productData['serving_quantity'] ?? null,
                    'nutriscore_score' => $productData['nutriscore_score'] ?? null,
                    'nutriscore_grade' => $productData['nutriscore_grade'] ?? null,
                    'main_category' => $productData['main_category'] ?? null,
                    'image_url' => $productData['image_url'] ?? null,
                ];

                $product = Product::create($filteredData);

            } catch (\Exception $e) {
                Log::error("Erro ao criar produto. Dados: " . json_encode($productData) . " | Erro: " . $e->getMessage());
            }
        }
    }

    public function createProduct($productData): void
    {
        try {
            $filteredData = [
                'code' => $this->extractNumbers($productData['code']),
                'status' => $productData['status'] ?? 'draft',
                'imported_t' => $productData['imported_t'] ?? now(),
                'url' => $productData['url'] ?? null,
                'creator' => $productData['creator'] ?? null,
                'created_t' => $productData['created_t'] ?? null,
                'last_modified_t' => $productData['last_modified_t'] ?? null,
                'product_name' => $productData['product_name'] ?? null,
                'quantity' => $productData['quantity'] ?? null,
                'brands' => $productData['brands'] ?? null,
                'categories' => $productData['categories'] ?? null,
                'labels' => $productData['labels'] ?? null,
                'cities' => $productData['cities'] ?? null,
                'purchase_places' => $productData['purchase_places'] ?? null,
                'stores' => $productData['stores'] ?? null,
                'ingredients_text' => $productData['ingredients_text'] ?? null,
                'traces' => $productData['traces'] ?? null,
                'serving_size' => $productData['serving_size'] ?? null,
                'serving_quantity' => $productData['serving_quantity'] ?? null,
                'nutriscore_score' => $productData['nutriscore_score'] ?? null,
                'nutriscore_grade' => $productData['nutriscore_grade'] ?? null,
                'main_category' => $productData['main_category'] ?? null,
                'image_url' => $productData['image_url'] ?? null,
            ];

            Product::create($filteredData);

        } catch (\Exception $e) {
            Log::error("Erro ao criar produto. Dados: " . json_encode($productData) . " | Erro: " . $e->getMessage());
        }
    }

    public function verifyProduct($code): bool {
        return !(Product::where('code', $this->extractNumbers($code))->first() == null);
    }

    public function extractNumbers($field): string
    {
        $pattern = '/[^0-9]/';
        return preg_replace($pattern, '', $field);
    }
}
