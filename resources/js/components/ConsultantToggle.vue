<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, ref } from 'vue';
const isConsultant = ref(false);

onMounted(() => {
    const savedState = localStorage.getItem('consultant_mode');
    if (savedState !== null) {
        isConsultant.value = JSON.parse(savedState);
    }
});

const toggleConsultantMode = async () => {
    try {
        const response = await axios.post('/toggle-consultant');
        isConsultant.value = response.data.consultant_mode;
        localStorage.setItem('consultant_mode', JSON.stringify(isConsultant.value));

        if (!isConsultant.value) {
            router.visit('/');
        } else {
            router.visit('/applications');
        }
    } catch (error) {
        console.error('Failed to toggle consultant mode', error);
    }
};
</script>

<template>
    <label for="consultant-toggle" class="switch px-3 text-sm text-gray-700">
        Consultant Mode
    </label>
    <label class="inline-flex cursor-pointer items-center">
        <input
            id="consultant-toggle"
            type="checkbox"
            class="peer sr-only"
            v-model="isConsultant"
            @change="toggleConsultantMode"
        />
        <div
            class="peer relative h-6 w-11 rounded-full bg-gray-200 peer-checked:bg-blue-600 peer-focus:ring-4 peer-focus:ring-blue-300 after:absolute after:start-[2px] after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white rtl:peer-checked:after:-translate-x-full dark:border-gray-600 dark:bg-gray-700 dark:peer-checked:bg-blue-600 dark:peer-focus:ring-blue-800"
        />
    </label>
</template>
