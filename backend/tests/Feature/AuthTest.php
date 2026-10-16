<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin'], ['label' => 'System Administrator']);
        Role::firstOrCreate(['name' => 'business_owner'], ['label' => 'Business Owner']);
        Role::firstOrCreate(['name' => 'customer'], ['label' => 'Customer']);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sign In');
        $response->assertSee('Welcome Back');
    }

    public function test_customer_registration_page_renders_successfully(): void
    {
        $response = $this->get('/register/customer');
        $response->assertStatus(200);
        $response->assertSee('Customer Account');
        $response->assertSee('Create Customer Account');
    }

    public function test_business_registration_page_renders_successfully(): void
    {
        $response = $this->get('/register/business');
        $response->assertStatus(200);
        $response->assertSee('Business Owner Account');
        $response->assertSee('Create Business Account');
    }

    public function test_customer_registration_creates_user_with_customer_role_and_logs_in(): void
    {
        $response = $this->post('/register/customer', [
            'name' => 'John Customer',
            'email' => 'john.cust@example.com',
            'phone' => '+1555123456',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'john.cust@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('John Customer', $user->name);
        $this->assertEquals('customer', $user->role->name);
        $this->assertTrue(Hash::check('SecurePass123!', $user->password));
    }

    public function test_customer_registration_rejects_duplicate_email(): void
    {
        $customerRole = Role::where('name', 'customer')->first();
        User::create([
            'name' => 'Existing User',
            'email' => 'duplicate@example.com',
            'phone' => '1234567890',
            'password' => 'Password123!',
            'role_id' => $customerRole->id,
            'status' => 'active',
        ]);

        $response = $this->from('/register/customer')->post('/register/customer', [
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'phone' => '0987654321',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect('/register/customer');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_customer_registration_requires_password_confirmation(): void
    {
        $response = $this->from('/register/customer')->post('/register/customer', [
            'name' => 'Mismatch Pass',
            'email' => 'mismatch@example.com',
            'phone' => '1234567890',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!',
        ]);

        $response->assertRedirect('/register/customer');
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    public function test_business_registration_creates_user_with_business_owner_role_and_pending_business(): void
    {
        $response = $this->post('/register/business', [
            'name' => 'Sara Founder',
            'email' => 'sara.founder@example.com',
            'phone' => '+1555987654',
            'password' => 'SecureOwner123!',
            'password_confirmation' => 'SecureOwner123!',
        ]);

        $response->assertRedirect(route('business.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'sara.founder@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('business_owner', $user->role->name);

        $business = Business::where('user_id', $user->id)->first();
        $this->assertNotNull($business);
        $this->assertEquals('pending', $business->status);
        $this->assertEquals($user->name, $business->owner_name);
        $this->assertEquals($user->email, $business->email);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $customerRole = Role::where('name', 'customer')->first();
        User::create([
            'name' => 'Login User',
            'email' => 'login.test@example.com',
            'phone' => '1234567890',
            'password' => 'SecretPass123!',
            'role_id' => $customerRole->id,
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'login.test@example.com',
            'password' => 'SecretPass123!',
        ]);

        $response->assertRedirect(route('customer.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_invalid_credentials_and_shows_error(): void
    {
        $customerRole = Role::where('name', 'customer')->first();
        User::create([
            'name' => 'Login User',
            'email' => 'login.test@example.com',
            'phone' => '1234567890',
            'password' => 'SecretPass123!',
            'role_id' => $customerRole->id,
            'status' => 'active',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'login.test@example.com',
            'password' => 'WrongPassword!',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $customerRole = Role::where('name', 'customer')->first();
        User::create([
            'name' => 'Banned User',
            'email' => 'banned@example.com',
            'phone' => '1234567890',
            'password' => 'SecretPass123!',
            'role_id' => $customerRole->id,
            'status' => 'banned',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'banned@example.com',
            'password' => 'SecretPass123!',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $customerRole = Role::where('name', 'customer')->first();
        $user = User::create([
            'name' => 'Logout User',
            'email' => 'logout.test@example.com',
            'phone' => '1234567890',
            'password' => 'SecretPass123!',
            'role_id' => $customerRole->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
