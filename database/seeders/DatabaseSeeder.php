<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\StoreSetting;
use App\Models\User;
use App\Enums\RoleEnums;
use App\Enums\StatusEnums;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@skynet.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => RoleEnums::Administrator->value,
                'status' => StatusEnums::Enable->value,
            ]
        );

        // Default Categories
        $categories = ['Electronics', 'Fashion', 'Home & Kitchen', 'Books'];
        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                ['name' => $category, 'description' => "Default $category category"]
            );
        }

        // 20 Dummy Products
        for ($i = 1; $i <= 20; $i++) {
            $baseName = 'Premium Digital Product ' . $i;
            \App\Models\Product::create([
                'category_id'         => rand(1, 4),
                'name'                => $baseName,
                'slug'                => \Illuminate\Support\Str::slug($baseName) . '-' . uniqid(),
                'url_key'             => \Illuminate\Support\Str::slug($baseName),
                'product_number'      => 'PRD-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'sku'                 => 'SKU-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'description'         => 'A beautifully crafted premium e-commerce item. Product #' . $i,
                'price'               => rand(5000, 150000),
                'cost_price'          => rand(2000, 4000),
                'discount_price'      => rand(0, 1) ? rand(1000, 4000) : null,
                'stock'               => rand(5, 50),
                'status'              => true,
                'is_new'              => $i <= 5,
                'is_featured'         => $i <= 3,
                'visible_individually' => true,
                'show_stock_level'    => true,
            ]);
        }

        // Default Store Settings
        $defaults = [
            // Payments
            'paystack_enabled'              => '1',
            'flutterwave_enabled'           => '1',
            'cod_enabled'                   => '1',
            // Tax & Fees
            'tax_enabled'                   => '1',
            'tax_rate'                      => '7.5',
            'service_fee_enabled'           => '0',
            'service_fee_amount'            => '0',
            // Shipping
            'shipping_enabled'              => '1',
            'waybill_fee'                   => '1500',
            'pickup_enabled'                => '1',
            'pickup_address'                => '123 Digital Hub Street, Delta State, Nigeria',
            'free_shipping_threshold'       => '50000',
            // Store Features
            'order_tracking_enabled'        => '1',
            'customer_registration_enabled' => '1',
            'shop_enabled'                  => '1',
            'guest_checkout_enabled'        => '1',
            'reviews_enabled'               => '0',
            'wishlist_enabled'              => '0',
            'tracking_number_prefix'        => 'SKY-',
            // Product display defaults
            'show_stock_level_default'      => '1',
        ];
        foreach ($defaults as $key => $value) {
            StoreSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
