<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner1;
    protected User $owner2;
    protected Business $biz1;
    protected Business $biz2;

    protected function setUp(): void
    {
        parent::setUp();

        $ownerRole = Role::firstOrCreate(['name' => 'business_owner'], ['label' => 'Business Owner']);

        $this->owner1 = User::create([
            'name' => 'Owner One',
            'email' => 'owner1@test.com',
            'phone' => '111',
            'password' => 'Pass123!',
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        $this->biz1 = Business::create([
            'user_id' => $this->owner1->id,
            'name' => 'Biz One',
            'owner_name' => $this->owner1->name,
            'email' => $this->owner1->email,
            'phone' => $this->owner1->phone,
            'status' => 'approved',
        ]);

        $this->owner2 = User::create([
            'name' => 'Owner Two',
            'email' => 'owner2@test.com',
            'phone' => '222',
            'password' => 'Pass123!',
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        $this->biz2 = Business::create([
            'user_id' => $this->owner2->id,
            'name' => 'Biz Two',
            'owner_name' => $this->owner2->name,
            'email' => $this->owner2->email,
            'phone' => $this->owner2->phone,
            'status' => 'approved',
        ]);
    }

    public function test_business_owner_can_create_product(): void
    {
        $response = $this->actingAs($this->owner1)->post('/business/products', [
            'name' => 'Cloud Server License',
            'description' => 'Dedicated cloud computing node',
            'price' => 199.99,
            'discount' => 10,
            'stock' => 50,
            'sku' => 'CLOUD-001',
            'availability' => 'in_stock',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'business_id' => $this->biz1->id,
            'name' => 'Cloud Server License',
            'sku' => 'CLOUD-001',
        ]);
    }

    public function test_business_owner_cannot_delete_other_business_product(): void
    {
        $product2 = Product::create([
            'business_id' => $this->biz2->id,
            'name' => 'Competitor Product',
            'description' => 'Should not be deletable by owner 1',
            'price' => 50.00,
            'stock' => 5,
            'availability' => 'in_stock',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->owner1)->delete("/business/products/{$product2->id}");
        $response->assertStatus(403);

        $this->assertDatabaseHas('products', ['id' => $product2->id]);
    }

    public function test_business_owner_can_create_service(): void
    {
        $response = $this->actingAs($this->owner1)->post('/business/services', [
            'name' => 'DevOps Consulting',
            'description' => 'Architecture and CI/CD audit',
            'price' => 500.00,
            'duration' => '2 hours',
            'availability' => 'available',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'business_id' => $this->biz1->id,
            'name' => 'DevOps Consulting',
        ]);
    }

    public function test_business_owner_cannot_delete_other_business_service(): void
    {
        $service2 = Service::create([
            'business_id' => $this->biz2->id,
            'name' => 'Competitor Service',
            'description' => 'Should not be deletable by owner 1',
            'price' => 300.00,
            'duration' => '1 hour',
            'availability' => 'available',
        ]);

        $response = $this->actingAs($this->owner1)->delete("/business/services/{$service2->id}");
        $response->assertStatus(403);

        $this->assertDatabaseHas('services', ['id' => $service2->id]);
    }
}
