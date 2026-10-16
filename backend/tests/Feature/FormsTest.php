<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\ContactMessage;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['label' => 'System Administrator']);
        $ownerRole = Role::firstOrCreate(['name' => 'business_owner'], ['label' => 'Business Owner']);
        Role::firstOrCreate(['name' => 'customer'], ['label' => 'Customer']);

        $category = BusinessCategory::create([
            'name' => 'Tech Services',
            'slug' => 'tech-services',
            'icon' => 'bi-laptop',
            'description' => 'IT and Tech',
            'status' => 'active',
        ]);

        $owner = User::create([
            'name' => 'Owner Sample',
            'email' => 'sample.owner@example.com',
            'phone' => '1234567890',
            'password' => 'Password123!',
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        Business::create([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'name' => 'Sample Tech Co',
            'owner_name' => $owner->name,
            'email' => 'sample@tech.com',
            'phone' => '1234567890',
            'status' => 'approved',
        ]);
    }

    public function test_public_pages_load_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/businesses')->assertStatus(200);
        $this->get('/products')->assertStatus(200);
        $this->get('/services')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
    }

    public function test_contact_form_creates_database_record(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Alice Tester',
            'email' => 'alice@test.com',
            'phone' => '+123456789',
            'subject' => 'Platform Question',
            'message' => 'I would like to inquire about registering my enterprise.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'alice@test.com',
            'name' => 'Alice Tester',
            'subject' => 'Platform Question',
        ]);
    }

    public function test_inquiry_form_creates_database_record(): void
    {
        $business = Business::first();

        $response = $this->post('/inquiry/send', [
            'business_id' => $business->id,
            'name' => 'Inquiry Sender',
            'email' => 'sender@inquiry.com',
            'phone' => '555-4321',
            'subject' => 'Quote Request',
            'message' => 'Please provide a pricing quote for cloud migration.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'business_id' => $business->id,
            'email' => 'sender@inquiry.com',
            'subject' => 'Quote Request',
        ]);
    }
}
