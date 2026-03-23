<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'
import Layout from '@/Layouts/Layout.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const flashMsg = usePage().props.flash.message;
const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const handleLogin = () => {
  form.post(route('customer.login.submit'), {
        onFinish: () => form.reset('password'),
        onError: () => toast.error(form.errors.email ?? 'Login failed. Please check credentials.'),
        onSuccess: () => {
            //toast.success('Login successful!');
            form.reset();
        }
    });
}
</script>
<template>
    <Layout>
        <div class="min-h-[70vh] flex items-center justify-center px-4 my-10">
            <div class="w-full max-w-md bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-md p-8 space-y-6">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Customer Login</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Welcome back! Access your orders and cart.</p>
                </div>
                <form @submit.prevent="handleLogin" class="mt-6 space-y-6">
                    <TextInput name="email" label="Email Address" v-model="form.email" type="email" placeholder="you@example.com" autofocus autocomplete="username" :required="true"/>
                    <TextInput name="password" label="Password" type="password" v-model="form.password" placeholder="••••••••" :required="true" autocomplete="current-password"/>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" v-model="form.remember" />
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-300">Remember me</span>
                        </label>
                    </div>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md transition" :disabled="form.processing">Sign in</button>
                </form>
                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                    Don't have an account?
                    <a :href="route('register')" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </Layout>
</template>
