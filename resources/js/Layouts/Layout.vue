<script setup>
import Navbar from '@/Components/Navigations/Navbar.vue'
import WhatsAppButton from '@/Components/WhatsAppButton.vue'
import ThemeToggle from '@/Components/ThemeToggle.vue';
import { onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import FlashMessages from '../Components/FlashMessages.vue';



</script>
<template>
    <div class=" bg-light text-black dark:bg-dark dark:text-white">
        <Navbar />
        <main class="pt-[132px]"> <!-- h-14 logo row + ~48px search row + ~36px category strip -->
            <slot />
            <WhatsAppButton v-if="!$page.props.auth.user" />
        </main>
        <FlashMessages/>
    </div>
</template>
