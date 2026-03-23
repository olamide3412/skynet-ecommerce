<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => StoreSetting::allAsArray(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            // Company Branding
            'company_name'                  => 'nullable|string|max:255',
            'company_email'                 => 'nullable|email|max:255',
            'company_phone'                 => 'nullable|string|max:50',
            'company_address'               => 'nullable|string|max:500',
            'company_tagline'               => 'nullable|string|max:500',
            
            // Store Features
            'order_tracking_enabled'        => 'boolean',
            'customer_registration_enabled' => 'boolean',
            'shop_enabled'                  => 'boolean',
            'guest_checkout_enabled'        => 'boolean',
            'reviews_enabled'               => 'boolean',
            'wishlist_enabled'              => 'boolean',
            'tracking_number_prefix'        => 'nullable|string|max:10',
            // Product display
            'show_stock_level_default'      => 'boolean',

            // Payment gateways
            'paystack_enabled'              => 'boolean',
            'flutterwave_enabled'           => 'boolean',
            'cod_enabled'                   => 'boolean',

            // Store charges
            'tax_enabled'                   => 'boolean',
            'tax_rate'                      => 'nullable|numeric|min:0|max:100',
            'service_fee_enabled'           => 'boolean',
            'service_fee_amount'            => 'nullable|numeric|min:0',

            // Shipping
            'shipping_enabled'              => 'boolean',
            'waybill_fee'                   => 'nullable|numeric|min:0',
            'pickup_enabled'                => 'boolean',
            'pickup_address'                => 'nullable|string|max:500',
            'free_shipping_threshold'       => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('company_logo')) {
            $request->validate(['company_logo' => 'image|max:2048']);
            $logoPath = $request->file('company_logo')->store('settings', 'public');
            StoreSetting::set('company_logo', $logoPath);
        }

        foreach ($data as $key => $value) {
            StoreSetting::set($key, is_bool($value) ? ($value ? '1' : '0') : ($value ?? ''));
        }

        return back()->with('success', 'Settings saved successfully.');
    }
}
