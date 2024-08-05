<?php

namespace Tests\Feature;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api_token')->plainTextToken;

        $this->currency = Currency::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_product_routes()
    {
        $response = $this->postJson('/api/products', [
            'title' => 'Test Product',
            'price' => 10.0,
            'currency_id' => 1,
        ]);

        $response->assertStatus(401);
    }

    public function test_product_creation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/products', [
            'title' => 'Test Product',
            'price' => 10.0,
            'currency_id' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['title' => 'Test Product']);
    }

    public function test_product_reading()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/products/' . $product->id);

        $response->assertStatus(200);

        $expectedResponse = [
            "success" => true,
            "data" => [
                "id" => $product->id,
                "title" => $product->title,
                "price" => $product->price,
                "currency_id" => $product->currency_id,
                "created_at" => $product->created_at->format('d/m/Y'),
                "updated_at" => $product->updated_at->format('d/m/Y'),
            ],
            "message" => "Product retrieved successfully."
        ];

        $response->assertJson($expectedResponse);
    }

    public function test_product_updating()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson('/api/products/' . $product->id, [
            'title' => 'Updated Title',
            'price' => $product->price,
            'currency_id' => $product->currency_id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['title' => 'Updated Title']);
    }

    public function test_product_deletion()
    {
        $product = Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson('/api/products/' . $product->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}

