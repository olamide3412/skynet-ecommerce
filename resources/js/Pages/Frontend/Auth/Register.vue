<script setup>
import { useForm } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue';
import TextInput from '@/Components/Forms/TextInput.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  phone: '',
});

const handleRegister = () => {
  form.post(route('customer.register.submit'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: () => toast.error('Registration failed. Check the form for errors.'),
        onSuccess: () => {
            toast.success('Registration successful!');
            form.reset();
        }
    });
}
</script>
<template>
    <Layout>
        <div class="min-h-[70vh] flex items-center justify-center px-4 my-10">
            <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-md p-8 space-y-6">
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Create an Account</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Join {{ $page.props.store_settings.company_name || 'Skynet' }} today.</p>
                </div>
                <form @submit.prevent="handleRegister" class="mt-6 space-y-6">
                    <TextInput name="name" label="Full Name" v-model="form.name" type="text" placeholder="John Doe" :message="form.errors.name" autofocus :required="true"/>
                    <TextInput name="email" label="Email Address" v-model="form.email" type="email" placeholder="you@example.com" :message="form.errors.email" :required="true"/>
                    <TextInput name="phone" label="Phone Number (Optional)" v-model="form.phone" type="text" placeholder="+234 800 000 0000" :message="form.errors.phone" />
                    <div class="grid grid-cols-2 gap-4">
                        <TextInput name="password" label="Password" type="password" v-model="form.password" placeholder="••••••••" :message="form.errors.password" :required="true"/>
                        <TextInput name="password_confirmation" label="Confirm Password" type="password" v-model="form.password_confirmation" placeholder="••••••••" :required="true"/>
                    </div>
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md transition" :disabled="form.processing">Register</button>
                </form>
                <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-4">
                    Already have an account?
                    <a :href="route('login')" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Sign in</a>
                </p>
            </div>
        </div>
    </Layout>
</template>
