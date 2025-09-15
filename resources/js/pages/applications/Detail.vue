<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { formatAmount } from '@/lib/utils';
import { Form, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Application {
    id: number;
    full_name: string;
    email: string;
    phone: string;
    date_of_birth: string;
    vehicle_make: string;
    vehicle_model: string;
    purchase_price: string | number;
    deposit_amount: string | number;
    term_months: number;
    status: string;
}

const { application } = defineProps<{ application: Application }>();

const form = useForm({
    status: application.status ?? 'submitted',
});

const loanAmount = computed(() => {
    const price = parseFloat(String(application.purchase_price || '0'));
    const deposit = parseFloat(String(application.deposit_amount || '0'));
    return (price - deposit).toFixed(2);
});

function updateStatus() {
    form.patch(`${application.id}/status`);
}
</script>

<template>
    <AppLayout>
        <div class="mx-auto my-8 max-w-3xl">
            <h1 class="mb-6 text-3xl font-bold text-gray-800">Application #{{ application.id }}</h1>

            <div class="grid grid-cols-1 gap-6 rounded-lg bg-white p-6 shadow-md md:grid-cols-2">
                <!-- Personal Info -->
                <div>
                    <h2 class="mb-3 text-lg font-semibold text-gray-700">Applicant Information</h2>
                    <dl class="space-y-2 text-gray-600">
                        <div>
                            <dt class="font-medium text-gray-800">Name</dt>
                            <dd>{{ application.full_name }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Email</dt>
                            <dd>{{ application.email }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Phone</dt>
                            <dd>{{ application.phone }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Date of Birth</dt>
                            <dd>{{ application.date_of_birth }}</dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h2 class="mb-3 text-lg font-semibold text-gray-700">Vehicle & Loan Details</h2>
                    <dl class="space-y-2 text-gray-600">
                        <div>
                            <dt class="font-medium text-gray-800">Vehicle</dt>
                            <dd>
                                {{ application.vehicle_make }} / {{ application.vehicle_model }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Purchase Price</dt>
                            <dd>{{ formatAmount(application.purchase_price) }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Deposit Amount</dt>
                            <dd>{{ formatAmount(application.deposit_amount) }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Term</dt>
                            <dd>{{ application.term_months }} months</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-800">Loan Amount</dt>
                            <dd class="font-semibold text-blue-600">
                                {{ formatAmount(loanAmount) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div
                class="mt-6 flex flex-col items-start gap-4 rounded-lg bg-white p-6 shadow-md md:flex-row md:items-center"
            >
                <Form
                    @submit.prevent="updateStatus"
                    v-slot="{ processing }"
                    class="flex w-full flex-col items-start gap-4 md:flex-row md:items-center"
                >
                    <label class="font-medium text-gray-700">Status</label>
                    <select
                        v-model="form.status"
                        class="rounded border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="submitted">Submitted</option>
                        <option value="in_review">In Review</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                    </select>
                    <button
                        type="submit"
                        :disabled="processing"
                        class="rounded bg-blue-600 px-4 py-2 text-white shadow transition hover:bg-blue-700"
                    >
                        Update
                    </button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
