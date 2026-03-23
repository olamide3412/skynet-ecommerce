<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({ settings: Object });

const form = useForm({
    _method: 'PUT',
    // Company Branding
    company_name:           props.settings.company_name || 'Skynet',
    company_tagline:        props.settings.company_tagline || '',
    company_email:          props.settings.company_email || 'support@skynetdigitalhub.com.ng',
    company_phone:          props.settings.company_phone || '+2348032072831',
    company_address:        props.settings.company_address || 'Delta State, Nigeria',
    company_logo:           null,
    // Payments
    paystack_enabled:       props.settings.paystack_enabled === '1',
    flutterwave_enabled:    props.settings.flutterwave_enabled === '1',
    cod_enabled:            props.settings.cod_enabled === '1',
    // Tax & Service Fee
    tax_enabled:            props.settings.tax_enabled === '1',
    tax_rate:               props.settings.tax_rate || '7.5',
    service_fee_enabled:    props.settings.service_fee_enabled === '1',
    service_fee_amount:     props.settings.service_fee_amount || '0',
    // Shipping
    shipping_enabled:       props.settings.shipping_enabled === '1',
    waybill_fee:            props.settings.waybill_fee || '1500',
    pickup_enabled:         props.settings.pickup_enabled === '1',
    pickup_address:         props.settings.pickup_address || '',
    free_shipping_threshold: props.settings.free_shipping_threshold || '50000',
    // Store Features
    order_tracking_enabled:         props.settings.order_tracking_enabled === '1',
    customer_registration_enabled:  props.settings.customer_registration_enabled === '1',
    shop_enabled:                   props.settings.shop_enabled === '1',
    guest_checkout_enabled:         props.settings.guest_checkout_enabled === '1',
    reviews_enabled:                props.settings.reviews_enabled === '1',
    wishlist_enabled:               props.settings.wishlist_enabled === '1',
    tracking_number_prefix:         props.settings.tracking_number_prefix || 'SKY-',
    show_stock_level_default:       props.settings.show_stock_level_default !== '0',
});

const handleLogoUpload = (e) => {
    form.company_logo = e.target.files[0];
};

const save = () => {
    form.post(route('admin.settings.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            //toast.success('Settings saved successfully!');
            form.company_logo = null;
        },
        onError: () => toast.error('Failed to save settings, please check the form.'),
    });
};

import { computed } from 'vue';

const storeFeatures = computed(() => ({
    shop_enabled:                  { label: 'Shop / Product Listing',   desc: 'Show the /shop page and product listings to visitors.' },
    order_tracking_enabled:        { label: 'Order Tracking',            desc: 'Allow customers to track orders by tracking number or ID.' },
    customer_registration_enabled: { label: 'Customer Registration',     desc: 'Allow new customers to create accounts.' },
    guest_checkout_enabled:        { label: 'Guest Checkout',            desc: 'Let customers checkout without creating an account.' },
    reviews_enabled:               { label: 'Product Reviews',           desc: 'Show and allow customer reviews on products.' },
    wishlist_enabled:              { label: 'Wishlist',                  desc: 'Let customers save products to a wishlist.' },
    show_stock_level_default:      { label: 'Show Stock Level (Default)', desc: 'Pre-fills the stock level toggle ON when creating new products.' },
}));

// Reusable toggle row component logic
const toggleSwitch = `w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600`;
</script>

<template>
    <Head title="Store Settings" />
    <AdminLayout>
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Store Settings</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Control all store features, payment methods, taxes, and shipping.</p>
                </div>
                <button @click="save" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-lg transition flex items-center gap-2 disabled:opacity-60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ form.processing ? 'Saving...' : 'Save Settings' }}
                </button>
            </div>

            <form @submit.prevent="save" class="space-y-8">

                <!-- ─── Company Branding ───────────────────────────────────── -->
                <section class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
                        <h2 class="text-lg font-semibold">🏢 Company Profile</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Settings here dynamically replace all branding across the site.</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name *</label>
                                <input type="text" v-model="form.company_name" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tagline / Slogan</label>
                                <input type="text" v-model="form.company_tagline" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Support Email</label>
                                    <input type="email" v-model="form.company_email" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Support Phone</label>
                                    <input type="text" v-model="form.company_phone" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Office Address</label>
                                <textarea v-model="form.company_address" rows="2" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2"></textarea>
                            </div>
                        </div>
                        
                        <!-- Right Column (Logo) -->
                        <div class="flex flex-col">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Logo</label>
                            <div class="flex-1 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900/50 flex flex-col items-center justify-center p-6 text-center hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                                <template v-if="props.settings.company_logo && !form.company_logo">
                                    <img :src="'/storage/' + props.settings.company_logo" class="h-20 object-contain mb-4" />
                                    <p class="text-xs text-green-600 font-semibold">Current Logo Active</p>
                                </template>
                                <template v-else>
                                    <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Upload a new logo</p>
                                    <p class="text-xs text-gray-500 mb-3">PNG, JPG up to 2MB</p>
                                </template>
                                <input type="file" @change="handleLogoUpload" accept="image/*" class="text-sm text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer w-full max-w-[200px]" />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ─── Store Features ─────────────────────────────────────── -->
                <section class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
                        <h2 class="text-lg font-semibold">🏪 Store Features</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Enable or disable major store sections and capabilities.</p>
                    </div>
                    <div class="p-6 space-y-4">

                        <!-- Feature toggle rows -->
                        <template v-for="(feature, key) in storeFeatures" :key="key">
                            <div class="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ feature.label }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ feature.desc }}</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" v-model="form[key]" class="sr-only peer" />
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </template>

                        <!-- Tracking number prefix -->
                        <div class="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">Tracking Number Prefix</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Prefix added before tracking numbers (e.g. SKY- → SKY-AB12CD34).</p>
                            </div>
                            <input type="text" v-model="form.tracking_number_prefix" maxlength="10"
                                class="w-28 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-center font-mono uppercase tracking-wider" />
                        </div>
                    </div>
                </section>

                <!-- ─── Payment Methods ─────────────────────────────────── -->
                <section class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
                        <h2 class="text-lg font-semibold">💳 Payment Methods</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Enable or disable payment options shown at checkout.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Paystack -->
                        <div class="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#00C3F7]/10 flex items-center justify-center">
                                    <span class="font-extrabold text-[#00C3F7] text-xs">PS</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Paystack</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cards, bank transfer, USSD</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.paystack_enabled" class="sr-only peer" />
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <!-- Flutterwave -->
                        <div class="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-[#F5A623]/10 flex items-center justify-center">
                                    <span class="font-extrabold text-[#F5A623] text-xs">FW</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Flutterwave</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cards, mobile money, bank</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.flutterwave_enabled" class="sr-only peer" />
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                        <!-- COD -->
                        <div class="flex items-center justify-between p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">Payment on Delivery (POD)</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Customer pays when order is delivered</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="form.cod_enabled" class="sr-only peer" />
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div>
                    </div>
                </section>

                <!-- ─── Tax & Service Fees ──────────────────────────────── -->
                <section class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
                        <h2 class="text-lg font-semibold">🧾 Tax & Service Fees</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">These fees are automatically added during checkout.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- VAT -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="form.tax_enabled" class="w-4 h-4 text-blue-600 rounded" />
                                    <span class="font-semibold text-gray-900 dark:text-white">Enable VAT / Tax</span>
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 ml-6">Automatically calculate and add tax to the order total.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="number" v-model="form.tax_rate" :disabled="!form.tax_enabled" min="0" max="100" step="0.5" class="w-24 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-center disabled:opacity-40" />
                                <span class="text-gray-600 dark:text-gray-300 font-bold">%</span>
                            </div>
                        </div>
                        <!-- Service Fee -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 border-t dark:border-gray-700 pt-6">
                            <div class="flex-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="form.service_fee_enabled" class="w-4 h-4 text-blue-600 rounded" />
                                    <span class="font-semibold text-gray-900 dark:text-white">Enable Service Fee</span>
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 ml-6">A flat fee applied to every order at checkout.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 dark:text-gray-300 font-bold">₦</span>
                                <input type="number" v-model="form.service_fee_amount" :disabled="!form.service_fee_enabled" min="0" class="w-32 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-center disabled:opacity-40" />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ─── Shipping & Delivery ────────────────────────────── -->
                <section class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60">
                        <h2 class="text-lg font-semibold">🚚 Shipping & Delivery</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Configure waybill charges and pickup options.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Waybill -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            <div class="flex-1">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" v-model="form.shipping_enabled" class="w-4 h-4 text-blue-600 rounded" />
                                    <span class="font-semibold text-gray-900 dark:text-white">Enable Waybill / Delivery Fee</span>
                                </label>
                                <p class="text-sm text-gray-500 dark:text-gray-400 ml-6">Charge customers a shipping fee for home delivery.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 dark:text-gray-300 font-bold">₦</span>
                                <input type="number" v-model="form.waybill_fee" :disabled="!form.shipping_enabled" min="0" class="w-32 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-center disabled:opacity-40" />
                            </div>
                        </div>
                        <!-- Free Shipping -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 border-t dark:border-gray-700 pt-4">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white">Free Shipping Threshold</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Orders above this amount get free shipping (set 0 to disable).</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-600 dark:text-gray-300 font-bold">₦</span>
                                <input type="number" v-model="form.free_shipping_threshold" :disabled="!form.shipping_enabled" min="0" class="w-36 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-center disabled:opacity-40" />
                            </div>
                        </div>
                        <!-- Pickup -->
                        <div class="border-t dark:border-gray-700 pt-6 space-y-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" v-model="form.pickup_enabled" class="w-4 h-4 text-blue-600 rounded" />
                                <span class="font-semibold text-gray-900 dark:text-white">Enable Store Pickup</span>
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400 ml-6">Allow customers to pick up their order at your store for free.</p>
                            <div v-if="form.pickup_enabled" class="ml-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pickup Address</label>
                                <textarea v-model="form.pickup_address" rows="2" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2" placeholder="e.g. 123 Digital Hub Street, Delta State"></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Save (bottom) -->
                <div class="flex justify-end">
                    <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg transition disabled:opacity-60">
                        {{ form.processing ? 'Saving...' : 'Save All Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
