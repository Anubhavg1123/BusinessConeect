<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\BusinessCategory;
use App\Models\Business;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Service;
use App\Models\Inquiry;
use App\Models\Review;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::create(['name' => 'admin', 'label' => 'System Administrator']);
        $ownerRole = Role::create(['name' => 'business_owner', 'label' => 'Business Owner']);
        $customerRole = Role::create(['name' => 'customer', 'label' => 'Customer']);

        // 2. Users
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@businessconnect.com',
            'phone' => '+1-800-555-0199',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'status' => 'active',
        ]);

        $owner1 = User::create([
            'name' => 'Alex Carter',
            'email' => 'alex@nexustech.com',
            'phone' => '+1-555-0147',
            'password' => Hash::make('password123'),
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        $owner2 = User::create([
            'name' => 'Sophia Bennett',
            'email' => 'sophia@greenleaf.com',
            'phone' => '+1-555-0188',
            'password' => Hash::make('password123'),
            'role_id' => $ownerRole->id,
            'status' => 'active',
        ]);

        $customer1 = User::create([
            'name' => 'David Miller',
            'email' => 'david.customer@gmail.com',
            'phone' => '+1-555-0192',
            'password' => Hash::make('password123'),
            'role_id' => $customerRole->id,
            'status' => 'active',
        ]);

        // 3. Business Categories
        $catTech = BusinessCategory::create([
            'name' => 'Information Technology',
            'slug' => 'technology',
            'icon' => 'bi-laptop',
            'description' => 'Software development, IT consulting, cloud infrastructure and cybersecurity.',
        ]);

        $catHealth = BusinessCategory::create([
            'name' => 'Healthcare & Wellness',
            'slug' => 'healthcare',
            'icon' => 'bi-heart-pulse',
            'description' => 'Hospitals, clinics, physical therapy, medical diagnostics and wellness centers.',
        ]);

        $catRealEstate = BusinessCategory::create([
            'name' => 'Real Estate & Living',
            'slug' => 'real-estate',
            'icon' => 'bi-building-gear',
            'description' => 'Commercial properties, luxury apartments, architecture and land development.',
        ]);

        $catRetail = BusinessCategory::create([
            'name' => 'Retail & Consumer Goods',
            'slug' => 'retail',
            'icon' => 'bi-cart-check',
            'description' => 'Electronics, apparel, home decor, lifestyle products and luxury goods.',
        ]);

        // 4. Product Categories
        $prodCatElectronics = ProductCategory::create(['name' => 'Electronics & Gadgets', 'slug' => 'electronics']);
        $prodCatSoftware = ProductCategory::create(['name' => 'Software Licenses', 'slug' => 'software']);

        // 5. Businesses
        $biz1 = Business::create([
            'user_id' => $owner1->id,
            'category_id' => $catTech->id,
            'name' => 'NexusTech Solutions Ltd',
            'owner_name' => 'Alex Carter',
            'description' => 'NexusTech is an industry-leading software and cloud transformation consulting firm empowering global enterprises.',
            'gst_number' => '27ABCDE1234F1Z5',
            'website' => 'https://nexustech.example.com',
            'email' => 'contact@nexustech.com',
            'phone' => '+1-555-0147',
            'whatsapp' => '+1-555-0147',
            'location' => 'Tech Park Tower A, San Francisco, CA',
            'opening_time' => '09:00:00',
            'closing_time' => '18:00:00',
            'state' => 'California',
            'city' => 'San Francisco',
            'address' => '100 Silicon Way, Suite 400',
            'pin_code' => '94105',
            'status' => 'approved',
            'views_count' => 1420,
        ]);

        $biz2 = Business::create([
            'user_id' => $owner2->id,
            'category_id' => $catHealth->id,
            'name' => 'Green Leaf Organic Wellness',
            'owner_name' => 'Sophia Bennett',
            'description' => 'Premium organic health supplements, holistic therapy, and eco-friendly wellness products.',
            'gst_number' => '27XYZAB5678G2Z9',
            'website' => 'https://greenleafwellness.example.com',
            'email' => 'hello@greenleaf.com',
            'phone' => '+1-555-0188',
            'whatsapp' => '+1-555-0188',
            'location' => 'Main Street Plaza, Austin, TX',
            'opening_time' => '08:30:00',
            'closing_time' => '20:00:00',
            'state' => 'Texas',
            'city' => 'Austin',
            'address' => '450 Organic Boulevard',
            'pin_code' => '78701',
            'status' => 'approved',
            'views_count' => 890,
        ]);

        // 6. Products
        Product::create([
            'business_id' => $biz1->id,
            'category_id' => $prodCatSoftware->id,
            'name' => 'Enterprise CRM Cloud License',
            'description' => 'Automate sales pipelines, customer relations, and multi-channel marketing campaigns.',
            'price' => 499.00,
            'discount' => 10.00,
            'stock' => 100,
            'sku' => 'NEX-CRM-01',
            'availability' => 'in_stock',
            'status' => 'active',
        ]);

        Product::create([
            'business_id' => $biz2->id,
            'category_id' => $prodCatElectronics->id,
            'name' => 'Organic Hemp Herbal Oil Extract',
            'description' => 'Cold-pressed 100% pure organic hemp oil formula for maximum stress relief and vitality.',
            'price' => 65.00,
            'discount' => 5.00,
            'stock' => 250,
            'sku' => 'GL-HEMP-500',
            'availability' => 'in_stock',
            'status' => 'active',
        ]);

        // 7. Services
        Service::create([
            'business_id' => $biz1->id,
            'name' => 'Custom Cloud Infrastructure Audit',
            'description' => 'Complete security audit, cost optimization, and DevOps pipeline setup for AWS / Azure.',
            'price' => 1200.00,
            'duration' => '3 Days',
            'availability' => 'available',
        ]);

        Service::create([
            'business_id' => $biz2->id,
            'name' => 'Holistic Wellness Consultation',
            'description' => 'Personalized 1-on-1 diet, nutritional evaluation, and lifestyle optimization plan.',
            'price' => 150.00,
            'duration' => '60 Minutes',
            'availability' => 'available',
        ]);

        // 8. Inquiries
        Inquiry::create([
            'customer_id' => $customer1->id,
            'business_id' => $biz1->id,
            'name' => 'David Miller',
            'email' => 'david.customer@gmail.com',
            'phone' => '+1-555-0192',
            'subject' => 'Quote request for custom CRM implementation',
            'message' => 'Hello, our company has 50 sales reps. We would like a full migration quote to NexusTech CRM.',
            'preferred_contact_time' => 'Morning (9 AM - 12 PM)',
            'status' => 'replied',
            'reply_message' => 'Thank you David! Our senior solutions engineer will contact you tomorrow morning.',
            'replied_at' => now(),
        ]);

        // 9. Reviews
        Review::create([
            'customer_id' => $customer1->id,
            'business_id' => $biz1->id,
            'rating' => 5,
            'review_text' => 'Outstanding IT support and cloud migration service. NexusTech saved us over 30% in infrastructure costs!',
            'status' => 'approved',
            'likes_count' => 14,
        ]);
    }
}
