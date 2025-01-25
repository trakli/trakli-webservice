<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_user_can_create_expense_categories()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'expense',
            'name' => 'Expense Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'name',
                    'description',
                    'user_id',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('expense_categories', ['id' => $response->json('data.id')]);
    }

    public function test_api_user_can_get_their_categories()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'expense',
            'name' => 'Expense Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201);

        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'income',
            'name' => 'Income Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201);

        $response = $this->actingAs($user)->get('/api/v1/categories?type=expense');

        $response->assertStatus(200);

        $response = $this->actingAs($user)->get('/api/v1/categories?type=income');

        $response->assertStatus(200);
    }

    public function test_api_user_can_update_their_expense_categories()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'expense',
            'name' => 'Expense Category',
            'description' => 'description',
        ]);
        $response->assertStatus(201);

        $response = $this->actingAs($user)->putJson('/api/v1/categories/'.$response->json('data.id'), [
            'type' => 'expense',
            'name' => 'Updated Expense Category',
            'description' => 'Updated description',
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('expense_categories', ['name' => $response->json('data.name'), 'description' => $response->json('data.description')]);
    }

    public function test_api_user_can_update_their_income_categories()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'income',
            'name' => 'Income Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201);

        $response = $this->actingAs($user)->putJson('/api/v1/categories/'.$response->json('data.id'), [
            'type' => 'income',
            'name' => 'Updated Income Category',
            'description' => 'Updated description',
        ]);

        $this->assertDatabaseHas('income_categories', ['name' => $response->json('data.name'), 'description' => $response->json('data.description')]);
    }

    public function test_api_user_can_delete_their_expense_categories()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'expense',
            'name' => 'Expense Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201);

        $response = $this->actingAs($user)->delete('/api/v1/categories/'.$response->json('data.id').'?type=expense');

        $response->assertStatus(204);
    }

    public function test_api_user_can_delete_their_income_categories()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'income',
            'name' => 'Income Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201);

        $response = $this->actingAs($user)->delete('/api/v1/categories/'.$response->json('data.id').'?type=income');

        $response->assertStatus(204);
    }

    public function test_api_user_can_create_income_categories()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', [
            'type' => 'income',
            'name' => 'Income Category',
            'description' => 'description',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'name',
                    'description',
                    'user_id',
                ],
                'message',
            ]);

        $this->assertDatabaseHas('income_categories', ['id' => $response->json('data.id')]);
    }

    public function test_api_user_can_not_create_categories_with_missing_information()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/categories', []);

        $response->assertStatus(400);
    }
}
