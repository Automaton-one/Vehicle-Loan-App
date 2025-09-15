<script setup lang="ts">
import ConsultantToggle from '@/components/ConsultantToggle.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <nav class="flex min-w-screen items-center justify-end bg-white px-6 py-3 shadow">
            <ConsultantToggle />
        </nav>
        <main class="flex-1 p-6">
            <slot />
        </main>
    </div>
</template>
