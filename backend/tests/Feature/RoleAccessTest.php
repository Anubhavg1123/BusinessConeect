<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $ownerUser;
    protected User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['label' => 'System Administrator']);
        $ownerRole = Role::firstOrCreate(['name' => 'business_owner'], ['label' => 'Business Owner']);
        $customerRole = Role::firstOrCreate(['name' => 'customer'], ['label' => 'Customer']);

        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'phone' => '111',
            'password' => 'Password123!',
            'role_id' => $adminRole->id,
            'status' => 'active',
        ]);

        $this->ownerUser = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'phone' => '222',
            'password' => 'Password123!',
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        Business::create([
            'user_id' => $this->ownerUser->id,
            'name' => 'Owner Test Business',
            'owner_name' => $this->ownerUser->name,
            'email' => $this->ownerUser->email,
            'phone' => $this->ownerUser->phone,
            'status' => 'approved',
        ]);

        $this->customerUser = User::create([
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'phone' => '333',
            'password' => 'Password123!',
            'role_id' => $customerRole->id,
            'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/customer/dashboard')->assertRedirect(route('login'));
        $this->get('/business/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customerUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_customer_cannot_access_business_dashboard(): void
    {
        $response = $this->actingAs($this->customerUser)->get('/business/dashboard');
        $response->assertStatus(403);
    }

    public function test_business_owner_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->ownerUser)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_customer_can_access_customer_dashboard(): void
    {
        $response = $this->actingAs($this->customerUser)->get('/customer/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Customer Portal');
    }

    public function test_business_owner_can_access_business_dashboard(): void
    {
        $response = $this->actingAs($this->ownerUser)->get('/business/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Business Owner Dashboard');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Analytics Dashboard');
    }
}
