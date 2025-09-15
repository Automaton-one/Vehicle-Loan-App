<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { formatAmount, formatDate } from '@/lib/utils';
import { Form, router, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

interface Pagination<T> {
    data: T[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
}

interface ApplicationItem {
    id: number;
    full_name: string;
    email: string;
    vehicle_make: string;
    vehicle_model: string;
    purchase_price: string | number;
    term_months: number;
    status: string;
    submitted_at: string;
}

const props = defineProps<{ items: Pagination<ApplicationItem> }>();
const items = ref(props.items);

const form = useForm({
    search: '',
    vehicle_type: '',
    status: '',
});

function searchAll() {
    form.get('/applications', {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            items.value = page.props.items as Pagination<ApplicationItem>;
        },
    });
}

function go(id: number) {
    router.visit('/applications/' + id);
}

function visit(url: string) {
    router.visit(url, {
        preserveState: true,
        replace: true,
        onSuccess: (page) => {
            items.value = page.props.items as Pagination<ApplicationItem>;
        },
    });
}

const loading = ref(true);

onMounted(() => {
    setTimeout(() => {
        loading.value = false;
    }, 1000);
});

function destroy(id: number) {
    router.delete(`/applications/${id}`, {
        preserveScroll: true,
        onSuccess: () => {
            items.value.data = items.value.data.filter((i) => i.id !== id);
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h1 class="text-3xl font-bold text-gray-800">Loan Applications</h1>
            </div>

            <Form @submit.prevent="searchAll" v-slot="{ processing }" class="loan-form mb-6">
                <div class="flex flex-col gap-3 sm:flex-row">
                    <input
                        v-model="form.search"
                        placeholder="Search name, email, make, or model..."
                        class="flex-1 rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        :disabled="processing"
                    />

                    <select
                        v-model="form.vehicle_type"
                        class="rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        :disabled="processing"
                    >
                        <option value="">All Vehicle Types</option>
                        <option value="car">Car</option>
                        <option value="ute">Ute</option>
                        <option value="truck">Truck</option>
                        <option value="van">Van</option>
                        <option value="suv">SUV</option>
                    </select>

                    <select
                        v-model="form.status"
                        class="rounded-lg border border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        :disabled="processing"
                    >
                        <option value="">All Statuses</option>
                        <option value="submitted">Submitted</option>
                        <option value="in_review">In Review</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                    </select>

                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 font-medium text-white shadow transition hover:bg-blue-700 disabled:opacity-50"
                        :disabled="processing"
                    >
                        {{ processing ? 'Searching...' : 'Search' }}
                    </button>
                </div>
            </Form>

            <div v-if="loading" class="animate-pulse">
                <div class="mb-4 h-10 rounded-lg bg-gray-300"></div>
                <div class="mb-3 h-6 rounded-lg bg-gray-300"></div>
                <div class="mb-3 h-6 rounded-lg bg-gray-300"></div>
                <div class="mb-3 h-6 rounded-lg bg-gray-300"></div>
                <div class="mb-3 h-6 rounded-lg bg-gray-300"></div>
                <div class="mb-3 h-6 rounded-lg bg-gray-300"></div>
            </div>

            <div v-else class="overflow-x-auto rounded-lg bg-white shadow">
                <table
                    id="loan-table"
                    class="w-full border-collapse text-left text-sm text-gray-700"
                >
                    <thead>
                        <tr class="bg-gray-100 text-gray-800">
                            <th class="p-3">ID</th>
                            <th class="p-3">Name</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Make/Model</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Term</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Submitted At</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in items.data"
                            :key="item.id"
                            @click="go(item.id)"
                            class="cursor-pointer border-t transition hover:bg-gray-50"
                        >
                            <td class="p-3 font-medium text-gray-900">{{ item.id }}</td>
                            <td class="p-3">{{ item.full_name }}</td>
                            <td class="p-3">{{ item.email }}</td>
                            <td class="p-3">{{ item.vehicle_make }} / {{ item.vehicle_model }}</td>
                            <td class="p-3">${{ formatAmount(item.purchase_price) }}</td>
                            <td class="p-3">{{ item.term_months }} mo</td>
                            <td>
                                <span
                                    class="rounded-lg px-2 py-1 text-xs font-semibold"
                                    :class="{
                                        'bg-green-100 text-green-700': item.status === 'approved',
                                        'bg-yellow-100 text-yellow-700':
                                            item.status === 'in_review',
                                        'bg-red-100 text-red-700': item.status === 'declined',
                                        'bg-gray-100 text-gray-600': item.status === 'submitted',
                                    }"
                                >
                                    {{ item.status }}
                                </span>
                            </td>
                            <td class="p-3 text-sm text-gray-500">
                                {{ formatDate(item.submitted_at) }}
                            </td>
                            <td class="p-3 text-center">
                                <button
                                    @click.stop="destroy(item.id)"
                                    class="rounded-full p-2 text-red-600 transition hover:bg-red-50 hover:text-red-700"
                                    title="Delete"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0
                     01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7-2h8m-9 2V5
                     a2 2 0 012-2h4a2 2 0 012 2v2"
                                        />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button
                    v-if="items.prev_page_url"
                    @click="visit(items.prev_page_url)"
                    class="rounded-lg bg-gray-200 px-4 py-2 font-medium shadow transition hover:bg-gray-300"
                >
                    Prev
                </button>
                <button
                    v-if="items.next_page_url"
                    @click="visit(items.next_page_url)"
                    class="rounded-lg bg-gray-200 px-4 py-2 font-medium shadow transition hover:bg-gray-300"
                >
                    Next
                </button>
            </div>
        </div>
    </AppLayout>
</template>
